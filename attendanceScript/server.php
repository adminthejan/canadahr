
<?php
set_time_limit(0);

$host = "0.0.0.0"; // Listen on all interfaces
$port = 8090; // Ensure this matches the device settings

// Create a TCP socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, $host, $port);
socket_listen($socket);

echo "HTTP Server started on $host:$port...\n";

while (true) {
    $client = socket_accept($socket);
    $request = socket_read($client, 4096); // Read full HTTP request

    if ($request) {
        echo "Received Request:\n$request\n";

        // Extract the request method (GET or POST)
        preg_match('/^(GET|POST) (.*?) HTTP/', $request, $matches);
        $method = $matches[1] ?? '';
        $url = $matches[2] ?? '';

        // Check if it's a request to "/iclock/cdata"
        if (strpos($url, "/iclock/cdata") !== false) {
            if ($method == "GET") {
                echo "Received a GET request (probably a status check)\n";
                $response = "HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nOK";
            } elseif ($method == "POST") {
                // Extract the punch data from the request body
                preg_match("/\r\n\r\n(.*)/s", $request, $matches);
                $punchData = trim($matches[1] ?? '');

                if (!empty($punchData)) {
                    echo "Extracted Punch Data:\n$punchData\n";
  // Convert punch data into JSON format for Laravel
  $formattedData = formatPunchData($punchData);
  if (!empty($formattedData)) {
    // Send the extracted punch data to the external API
    $apiUrl = "https://hr.jaan.lk/api/attendance/store";
    $responseFromAPI = sendToAPI($apiUrl, $formattedData);

    echo "API Response:\n$responseFromAPI\n";
} else {
    echo "Invalid punch data format. Skipping API request.\n";
}

                } else {
                    echo "No punch data found in POST request.\n";
                }

                              // Respond with HTTP 200 OK
                $response = "HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nOK";
            }
        } else {
            echo "Unknown request received.\n";
            $response = "HTTP/1.1 404 Not Found\r\nContent-Length: 9\r\n\r\nNot Found";
        }

        // Send the HTTP response
        socket_write($client, $response, strlen($response));
    }

    socket_close($client);
}

socket_close($socket);

/**
 * Formats the raw punch data into JSON structure required by Laravel API.
 */
function formatPunchData($punchData)
{
    $entries = explode("\n", $punchData);
    $formattedEntries = [];

    foreach ($entries as $entry) {
        $entry = trim($entry);
        if (!empty($entry)) {
            // Assume format: "EmpId=12345,AttTime=2025-02-15 08:30:00"
            parse_str(str_replace(",", "&", $entry), $data);

            if (isset($data['EmpId']) && isset($data['AttTime'])) {
                $formattedEntries[] = [
                    'EmpId' => $data['EmpId'],
                    'AttTime' => $data['AttTime']
                ];
            }
        }
    }

    return !empty($formattedEntries) ? json_encode($formattedEntries) : null;
}

/**
 * Send formatted punch data to the external API.
 */
function sendToAPI($url, $jsonData)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $response = 'Curl error: ' . curl_error($ch);
    }
    curl_close($ch);

    return $response;
}
?>
