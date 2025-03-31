<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Employee;
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
     // Decode JSON input
     $data = $request->json()->all();
 
     // Ensure $data is always an array
     if (!is_array($data)) {
         return response()->json(['error' => 'Invalid data format'], 400);
     }
 
     // If $data is a single object, wrap it in an array
     if (isset($data['EmpId'])) {
         $data = [$data];
     }
 
     // Process each record
     foreach ($data as $entry) {
         // Validate required fields
         if (!isset($entry['EmpId']) || !isset($entry['AttTime'])) {
             return response()->json(['error' => 'Missing required fields: EmpId or AttTime'], 400);
         }
 
         // Extract fields
         $employeeId = $entry['EmpId'];
         $attFullData = $entry['AttTime'];
 
         // Ensure employee exists
         $employee = Employee::where('id', $employeeId)->first();
         if (!$employee) {
             return response()->json(['error' => "Employee ID {$employeeId} not found"], 404);
         }
 
         // Split AttFullData into date and time
         [$attDate, $attTime] = explode(" ", $attFullData);
 
         // Convert to Carbon objects
         $attTimeCarbon = Carbon::parse($attFullData); // Marked attendance time
         $lateThreshold = Carbon::createFromTime(8, 45, 0);
         $overtimeThreshold = Carbon::createFromTime(16, 45, 0);
         $workHoursThreshold = 8; // Standard working hours
         // Check if attendance already exists for the day
         $attendanceRecord = Attendance::where('employee_id', $employeeId)
             ->where('date', $attDate)
             ->first();
 
         if (!$attendanceRecord) {
             // First entry of the day - set as clock-in
             $lateBySeconds = $attTimeCarbon->greaterThan($lateThreshold)
                 ? $attTimeCarbon->diffInSeconds($lateThreshold)
                 : 0;
 
             Attendance::create([
                 'employee_id' => $employeeId,
                 'date' => $attDate,
                 'clock_in_time' => $attTime,
                 'clock_out_time' => null,
                 'status' => 1,
                 'total_work_hours' => null,
                 'overtime_seconds' => null,
                 'late_by_seconds' => $lateBySeconds,
             ]);
         } else {
             // Update clock-out time with the latest timestamp
             $clockInTimeCarbon = Carbon::parse($attendanceRecord->clock_in_time);
             $clockOutTimeCarbon = $attTimeCarbon; // Latest clock-out time
 
             // Calculate total work hours
             $totalWorkHours = $clockInTimeCarbon->diffInSeconds($clockOutTimeCarbon) / 3600; // Convert to hours

             // Calculate overtime only if total work hours exceed 8
             $overtimeSeconds = 0;
             if ($totalWorkHours > $workHoursThreshold) {
                 $overtimeSeconds = ($totalWorkHours - $workHoursThreshold) * 3600; // Convert excess hours to seconds
             }
 
 
             // Update existing record
             $attendanceRecord->update([
                 'clock_out_time' => $attTime,
                 'status' => 1,
                 'total_work_hours' => $totalWorkHours, // No rounding off
                 'overtime_seconds' => $overtimeSeconds,
             ]);
         }
     }
 
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
