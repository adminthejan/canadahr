<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeManagerSeeder extends Seeder
{
    public function run()
    {
        // Get all employees
        $employees = Employee::all();

        // Initialize a queue with a root manager (or the first employee as the top-level manager)
        $managerQueue = [$employees->first()->id]; // Start with the first employee as a top-level manager

        foreach ($employees->skip(1) as $employee) { // Skip the first employee as it is the root
            // Assign the current employee a manager from the queue
            $employee->update([
                'manager_id' => $managerQueue[0], // Use the first manager in the queue
            ]);

            // Add the current employee to the queue to manage future subordinates
            $managerQueue[] = $employee->id;

            // Limit each manager to a maximum of 3 subordinates
            if (Employee::where('manager_id', $managerQueue[0])->count() >= 3) {
                array_shift($managerQueue); // Remove the manager if they already have 3 subordinates
            }
        }
    }
}
