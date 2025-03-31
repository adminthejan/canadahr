<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Education;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'degree' => $this->faker->randomElement(['BSc Software Engineering', 'MSc Computer Science', 'PhD Artificial Intelligence']),
            'institution' => $this->faker->randomElement(['University of Westminster', 'MIT', 'Stanford University']),
            'graduation_year' => $this->faker->year(),
            'work_experience_years' => $this->faker->randomElement(['2', '2', '5']),
            'work_experience_role' => $this->faker->jobTitle(),
            'work_experience_company' => $this->faker->company(),
            'course_name' => $this->faker->randomElement(['Advanced AI', 'Cloud Computing', 'Cybersecurity Fundamentals']),
            'training_provider' => $this->faker->company(),
            'completion_date' => $this->faker->date(),
            'certification_status' => $this->faker->randomElement(['merit', 'distinction', 'pass']),
        ];
    }
}
