<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition()
    {
        return [
            'full_name' => $this->faker->sentence(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'date_of_birth' => $this->faker->date('Y-m-d', '2000-01-01'),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'department_id' => Department::factory(), // Generates a related department
            'manager_id' => null, // By default, no manager
            'employment_start_date' => $this->faker->date('Y-m-d', '-5 years'),
            'employment_end_date' => $this->faker->optional()->date('Y-m-d', '+5 years'),
            'status' => $this->faker->randomElement(['Active', 'Not Active']),
            'account_holder_name' => $this->faker->word(),
            'bank_name' => $this->faker->word(),
            'account_no' => $this->faker->word(),
            'branch_name' => $this->faker->word(),
        ];
    }
}

