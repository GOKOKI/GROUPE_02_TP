<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JuryMember>
 */
class JuryMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'thesis_defense_report_id' => \App\Models\ThesisDefenseReport::factory(),
            'professor_id' => \App\Models\Professor::factory(),
            'role' => fake()->randomElement(['president', 'secretary', 'member']),
            'grade' => fake()->optional()->randomFloat(2, 0, 20),
            'comments' => fake()->optional()->sentence(),
        ];
    }
}
