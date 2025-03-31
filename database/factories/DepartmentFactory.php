<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition()
    {
        return [
            'department_id' => $this->faker->word(),
            'name' => $this->faker->unique()->company(),
            'branch' => $this->faker->city(),
        ];
    }

    /**
     * Configure the factory to create a department with a parent.
     */
}