<?php

namespace Database\Factories;

use App\Models\SalaryComponent;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryComponentFactory extends Factory
{
    protected $model = SalaryComponent::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Basic Salary', 'Transport Allowance', 'Health Insurance', 'Tax Deduction', 'Other Allowance']),
            'type' => $this->faker->randomElement(['allowance', 'deduction']),
            'amount' => $this->faker->randomFloat(2, 50, 1000), // Random amount between 50 and 1000
        ];
    }
}

