<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ThesisDefenseReport>
 */
class ThesisDefenseReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => \App\Models\Student::factory(),
            'supervisor_id' => \App\Models\Professor::factory(),
            'title' => fake()->sentence(6),
            'abstract' => fake()->optional()->paragraph(),
            'defense_date' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'defense_time' => fake()->time('H:i'),
            'room' => fake()->optional()->word(),
            'final_grade' => fake()->optional()->randomFloat(2, 0, 20),
            'status' => fake()->randomElement(['scheduled', 'completed', 'cancelled']),
            'comments' => fake()->optional()->sentence(),
        ];
    }
}
