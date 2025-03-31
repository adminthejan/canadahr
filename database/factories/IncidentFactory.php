<?php

namespace Database\Factories;

use App\Models\Incident;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncidentFactory extends Factory
{
    protected $model = Incident::class;

    public function definition()
    {
        return [
            'employee_id' => Employee::factory(), // Generates a related employee
            'incident_type' => $this->faker->randomElement(['Safety Violation', 'Workplace Misconduct', 'Accident', 'Complaint']),
            'description' => $this->faker->optional()->paragraph(), // Optional incident description
            'incident_date' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'supporting_documents' => json_encode([
                'uploads/' . $this->faker->uuid . '.pdf', // Randomly generated file paths
                'uploads/' . $this->faker->uuid . '.pdf',
            ]), 
            'resolution_status' => $this->faker->optional()->randomElement(['Resolved', 'Unresolved', 'Under Investigation']),
        ];
    }
}
