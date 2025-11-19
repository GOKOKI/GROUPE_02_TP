<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory()->student(),
            'department_id' => \App\Models\Department::factory(),
            'student_number' => fake()->unique()->numerify('STU#####'),
            'phone' => fake()->phoneNumber(),
            'enrollment_date' => fake()->date(),
            'level' => fake()->randomElement(['Bachelor', 'Master', 'PhD']),
        ];
    }
}
