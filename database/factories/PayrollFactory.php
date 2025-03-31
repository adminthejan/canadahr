<?php

namespace Database\Factories;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollFactory extends Factory
{
    protected $model = Payroll::class;

    public function definition()
    {
        $basic_salary = $this->faker->randomFloat(2, 500, 5000); // Random basic salary
        $allowances = $this->faker->optional()->randomFloat(2, 50, 500); // Random allowances
        $deductions = $this->faker->optional()->randomFloat(2, 20, 300); // Random deductions
        $net_salary = $basic_salary + ($allowances ?? 0) - ($deductions ?? 0);

        return [
            'employee_id' => Employee::factory(), // Generates a related employee
            'payroll_month' => $this->faker->date('Y-m', 'now'), // Current or past month
            'basic_salary' => $basic_salary,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'net_salary' => $net_salary,
        ];
    }
}
