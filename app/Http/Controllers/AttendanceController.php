<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;

use Carbon\Carbon;


class AttendanceController extends Controller
{
    public function create()
{
    // Fetch all employees to associate with the attendance record
    //$employees = Employee::all();

    // Return the view to create a new attendance record
    return view('management.attendance.attendance-create');
}
    public function edit($id)
    {
        // Find the attendance record by ID
        $attendance = Attendance::findOrFail($id);
    
        // Retrieve the employee associated with this attendance record
        $employee = Employee::findOrFail($attendance->employee_id); // Assuming `employee_id` exists in the attendance table
      //  dd($employee);
        // Return the edit view with both attendance and employee data
        return view('management.attendance.attendance-edit', compact('attendance', 'employee'));
    }
    
  /*   public function store(Request $request)
{
    // Validate input to ensure correct format
    $request->validate([
        'employee_id' => 'required',
        'date' => 'required|date',

        'total_work_hours' => ['nullable', 'regex:/^([0-9]+):([0-5][0-9]):([0-5][0-9])$/'],
        'overtime_hours' => ['nullable', 'regex:/^([0-9]+):([0-5][0-9]):([0-5][0-9])$/'],
        'late_by' => ['nullable', 'regex:/^([0-9]+):([0-5][0-9]):([0-5][0-9])$/'],
    ]);


    // Convert HH:MM:SS to seconds
    $totalWorkSeconds = $this->convertToSeconds($request->input('total_work_hours'));
    $overtimeSeconds = $this->convertToSeconds($request->input('overtime_hours'));
    $lateBySeconds = $this->convertToSeconds($request->input('late_by'));

    try {
        $employee = Employee::where('employee_id', $request['employee_id'])->first();
        // Handle file uploads if supporting_documents exist
      
        // Create the attendance record
        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'date' => $request->input('date'),
            'clock_in_time' => $request->input('clock_in_time'),
            'clock_out_time' => $request->input('clock_out_time'),
            'total_work_hours' => $totalWorkSeconds,
            'overtime_seconds' => $overtimeSeconds,
            'late_by_seconds' => $lateBySeconds,
            'status' => 'present', // Default status
        ]);
    
        return redirect()->route('attendance.management')->with('success', 'Attendance record added successfully!');
    } catch (\Illuminate\Database\QueryException $e) {
        // Log the error and display a message
        \Log::error('Query Exception: ' . $e->getMessage());
        return redirect()->route('attendance.management')->with('error', 'Failed to add attendance record.'.$e->getMessage());
    } catch (\Exception $e) {
        // Catch any other exceptions
        \Log::error('Exception: ' . $e->getMessage());
        return redirect()->route('attendance.management')->with('error', 'An error occurred while adding the attendance record.'.$e->getMessage());
    }
}
 */

public function store(Request $request)
{
    $data = $request->json()->all();

    file_put_contents(storage_path('logs/attendance_payload.log'), now() . ' - ' . json_encode($data, JSON_PRETTY_PRINT).' request received end' . PHP_EOL, FILE_APPEND);

    if (!is_array($data)) {
        file_put_contents(storage_path('logs/error_attendance_payload.log'), now() . ' error - Invalid format: ' . json_encode($data, JSON_PRETTY_PRINT) . ' date format error end' . PHP_EOL, FILE_APPEND);
        return response()->json(['error' => 'Invalid data format'], 400);
    }

    if (isset($data['EmpId'])) {
        $data = [$data];
    }

    foreach ($data as $entry) {
        if (!isset($entry['EmpId']) || !isset($entry['AttTime'])) {
            file_put_contents(storage_path('logs/error_attendance_payload.log'), now() . ' Missing fields: ' . json_encode($data, JSON_PRETTY_PRINT) . ' missing fields error end' . PHP_EOL, FILE_APPEND);
            return response()->json(['error' => 'Missing required fields: EmpId or AttTime'], 400);
        }

        $employeeId = $entry['EmpId'];
        $attTime = $entry['AttTime'];

        $employee = Employee::find($employeeId);
        if (!$employee) {
            file_put_contents(storage_path('logs/error_attendance_payload.log'), now() . ' Employee not found: ' . json_encode($data, JSON_PRETTY_PRINT) . ' missing employee ID error end' . PHP_EOL, FILE_APPEND);
            return response()->json(['error' => "Employee ID {$employeeId} not found"], 404);
        }

        [$attDate, $attClockTime] = explode(' ', $attTime);
        $attTimeCarbon = Carbon::parse($attTime);

        $attendance = Attendance::where('employee_id', $employeeId)
            ->where('date', $attDate)
            ->first();

        if (!$attendance) {
            // First punch – treat as clock-in
            $lateThreshold = Carbon::parse($attDate . ' 08:45:00');
            $leaveThreshold = Carbon::parse($attDate . ' 09:15:00');

            $lateBySeconds = $attTimeCarbon->greaterThan($lateThreshold)
                ? $attTimeCarbon->diffInSeconds($lateThreshold)
                : 0;

            // Add leave if after 9:15
            if ($attTimeCarbon->greaterThan($leaveThreshold)) {
                $existingLeave = Leave::where('employee_id', $employee->id)
                    ->where('leave_type', 'late')
                    ->whereDate('start_date', $attDate)
                    ->first();

                if (!$existingLeave) {
                    Leave::create([
                        'employee_id' => $employee->id,
                        'employee_name' => $employee->full_name,
                        'employment_ID' => $employee->employee_id,
                        'leave_type' => 'late',
                        'approved_person' => 'System Auto',
                        'start_date' => $attDate,
                        'end_date' => $attDate,
                        'duration' => 1,
                        'status' => 'approved',
                        'description' => 'Auto-marked late for arriving after 9:15 AM',
                        'supporting_documents' => null,
                    ]);
                }
            }

            Attendance::create([
                'employee_id' => $employeeId,
                'date' => $attDate,
                'clock_in_time' => $attClockTime,
                'clock_out_time' => null,
                'status' => 'present',
                'total_work_hours' => null,
                'overtime_seconds' => null,
                'late_by_seconds' => $lateBySeconds,
            ]);
        } else {
            // Clock-out logic
            $clockIn = Carbon::parse($attendance->date . ' ' . $attendance->clock_in_time);
            $clockOut = $attTimeCarbon->copy();

            if ($clockOut->lessThan($clockIn)) {
                $clockOut->addDay(); // Adjust for next day clock out
            }

            $eightThirty = Carbon::parse($attendance->date . ' 08:30:00');
            $tenAM = Carbon::parse($attendance->date . ' 10:00:00');
            $lateThreshold = Carbon::parse($attendance->date . ' 08:45:00');

            if ($clockIn->greaterThanOrEqualTo($tenAM)) {
                $workStart = $clockIn->copy();
                $otThreshold = 4 * 3600;
            } else {
                $workStart = $clockIn->lessThan($eightThirty) ? $eightThirty->copy() : $clockIn->copy();
                $otThreshold = 8 * 3600;
            }

            $totalWorkSeconds = $workStart->diffInSeconds($clockOut);
            $overtimeSeconds = $totalWorkSeconds > $otThreshold ? $totalWorkSeconds - $otThreshold : 0;
            $lateBySeconds = $clockIn->greaterThan($lateThreshold) ? $clockIn->diffInSeconds($lateThreshold) : 0;

            $attendance->update([
                'clock_out_time' => $clockOut->format('H:i:s'),
                'status' => 'present',
                'total_work_hours' => $totalWorkSeconds,
                'overtime_seconds' => $overtimeSeconds,
                'late_by_seconds' => $lateBySeconds,
            ]);
        }
    }

    file_put_contents(storage_path('logs/success_attendance_payload.log'), now() . ' - ' . json_encode($data, JSON_PRETTY_PRINT) . ' request successfully processed end' . PHP_EOL, FILE_APPEND);

    return response()->json(['message' => 'Records processed successfully'], 201);
}



 public function update(Request $request, $id)
 {
     $attendance = Attendance::findOrFail($id);

     // Validate input to ensure correct format
     $request->validate([
         'employee_id' => 'required|numeric',
         'clock_in_time' => 'required',
         'clock_out_time' => 'required',
         'date' => 'required|date',
     ]);

     // Convert clock-in and clock-out times to Carbon instances
     $clockInTime = Carbon::parse($request->input('clock_in_time'));
     $clockOutTime = Carbon::parse($request->input('clock_out_time'));
     
     // Ensure valid clock-in and clock-out times
     if ($clockInTime->greaterThanOrEqualTo($clockOutTime)) {
         return redirect()->route('attendance.management')->with('error', 'Clock-out time must be after clock-in time.');
     }

     // Calculate total work hours in seconds
     $totalWorkSeconds = $clockInTime->diffInSeconds($clockOutTime);
     
     // Define thresholds
     $lateThreshold = Carbon::createFromTime(8, 45, 0); // 8:45 AM late threshold
     $regularWorkSeconds = 8 * 3600; // 8 hours in seconds
     
     // Calculate late by seconds
     $lateBySeconds = $clockInTime->greaterThan($lateThreshold)
         ? $clockInTime->diffInSeconds($lateThreshold)
         : 0;

     // Calculate overtime seconds (only if total work exceeds 8 hours)
     $overtimeSeconds = $totalWorkSeconds > $regularWorkSeconds
         ? $totalWorkSeconds - $regularWorkSeconds
         : 0;
     
     try {
         $employee = Employee::where('employee_id', $request['employee_id'])->first();
         
         // Update the attendance record
         $isUpdated = $attendance->update([
             'employee_id' => $employee->id,
             'clock_in_time' => $request->input('clock_in_time'),
             'clock_out_time' => $request->input('clock_out_time'),
             'total_work_hours' => $totalWorkSeconds, // Total work in seconds
             'overtime_seconds' => $overtimeSeconds, // Overtime in seconds
             'late_by_seconds' => $lateBySeconds, // Late by in seconds
             'date' => $request->input('date'),
         ]);

         // Check if the update was successful
         if ($isUpdated) {
             return redirect()->route('attendance.management')->with('success', 'Attendance updated successfully!');
         } else {
             return redirect()->route('attendance.management')->with('error', 'Failed to update attendance record.');
         }
     } catch (\Illuminate\Database\QueryException $e) {
         // Log the error and display a message
         \Log::error('Query Exception: ' . $e->getMessage());
         return redirect()->route('attendance.management')->with('error', 'Database error occurred while updating attendance.' . $e->getMessage());
     } catch (\Exception $e) {
         // Catch any other exceptions
         \Log::error('Exception: ' . $e->getMessage());
         return redirect()->route('attendance.management')->with('error', 'An unexpected error occurred while updating attendance.' . $e->getMessage());
     }
 }

    
    
    /**
     * Convert HH:MM:SS format to seconds.
     *
     * @param string|null $time
     * @return int
     */
    private function convertToSeconds($time)
    {
        if (!$time) {
            return 0;
        }
    
        [$hours, $minutes, $seconds] = explode(':', $time);
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

}
