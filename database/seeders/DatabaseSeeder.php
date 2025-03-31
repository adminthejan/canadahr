<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\ExpenseClaim;
use App\Models\Leave;
use App\Models\Incident;
use App\Models\SalaryComponent;
use App\Models\Payroll;
use App\Models\Loan;
use App\Models\Education;


class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Department::factory(5)->create();
        Position::factory(5)->create();
        
        Employee::factory(5)->create();
        Attendance::factory(5)->create();
        ExpenseClaim::factory(5)->create();
        Leave::factory(5)->create();
        Incident::factory(5)->create();
        SalaryComponent::factory(5)->create();
       // Payroll::factory(5)->create();
        Loan::factory(5)->create();
        Education::factory(5)->create();
    
    }
}
