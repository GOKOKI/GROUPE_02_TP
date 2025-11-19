<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Professor>
 */
class ProfessorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory()->professor(),
            'department_id' => \App\Models\Department::factory(),
            'title' => fake()->randomElement(['Dr.', 'Prof.', 'Mr.', 'Ms.']),
            'specialty' => fake()->words(2, true),
            'phone' => fake()->phoneNumber(),
            'bio' => fake()->optional()->paragraph(),
        ];
    }
}
