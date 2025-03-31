<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employee::create([
            'full_name' => 'John Doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'date_of_birth' => '1987-10-19',
            'age' => '35',
            'gender' => 'Male',
            'title' => 'CEO',
            'employment_type' => 'Full-time',
            'image' => null,
            'current' => 'Active',
            'legal_documents' => null,
            'employee_id' => 'EMP001',
            'description' => 'Company CEO',
            'probation_start_date' => null,
            'probation_period' => null,
            'department_id' => null,
            'manager_id' => null, // No manager for the first employee
            'education_id' => null,
            'employment_start_date' => '2014-01-11',
            'employment_end_date' => null,
            'status' => 'Active',
            'account_holder_name' => 'John Doe',
            'bank_name' => 'ABC Bank',
            'account_no' => '123456789',
            'branch_name' => 'Main Branch',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
