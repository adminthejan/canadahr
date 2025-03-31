<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Auth')</title>
    @vite(['resources/js/app.js'])

</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    @yield('content') <!-- Content placeholder -->
</body>
</html>
