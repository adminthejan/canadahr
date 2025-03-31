<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition()
    {
        return [
            'title' => $this->faker->jobTitle(), // Random job title
            'description' => $this->faker->sentence(), // Random short description
            'salary_grade' => $this->faker->randomFloat(2, 500, 5000), // Random salary between 500 and 5000
        ];
    }
}
