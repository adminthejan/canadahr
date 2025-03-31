<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        $expectedClockIn = Carbon::createFromTime(9, 0, 0); // Standard clock-in time: 09:00 AM
        $clockInTime = $this->faker->optional(0.9)->dateTimeBetween('08:30:00', '12:00:00'); // 90% chance of having a clock-in time
        $clockOutTime = $this->faker->optional(0.9)->dateTimeBetween('16:00:00', '20:00:00'); // 90% chance of having a clock-out time

        $clockIn = $clockInTime ? Carbon::parse($clockInTime->format('H:i:s')) : null;
        $clockOut = $clockOutTime ? Carbon::parse($clockOutTime->format('H:i:s')) : null;

        // Calculate late_by in seconds
        $lateBySeconds = $clockIn && $clockIn->greaterThan($expectedClockIn)
            ? $clockIn->diffInSeconds($expectedClockIn)
            : 0;

        // Calculate total_work_hours in seconds
        $totalWorkSeconds = $clockIn && $clockOut
            ? $clockIn->diffInSeconds($clockOut)
            : 0;

        // Calculate overtime_seconds (assuming standard work hours: 8 hours or 28800 seconds)
        $standardWorkSeconds = 8 * 3600; // 8 hours in seconds
        $overtimeSeconds = $totalWorkSeconds > $standardWorkSeconds
            ? $totalWorkSeconds - $standardWorkSeconds
            : 0;

        return [
            'employee_id' => Employee::factory(), // Generates a related employee
            'date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'clock_in_time' => $clockIn ? $clockIn->format('H:i:s') : null,
            'clock_out_time' => $clockOut ? $clockOut->format('H:i:s') : null,
            'status' => $this->faker->randomElement(['present', 'absent', 'on leave']),
            'total_work_hours' => $totalWorkSeconds, // Stored in seconds
            'overtime_seconds' => $overtimeSeconds, // Stored in seconds
            'late_by_seconds' => $lateBySeconds, // Stored in seconds
        ];
    }
}
