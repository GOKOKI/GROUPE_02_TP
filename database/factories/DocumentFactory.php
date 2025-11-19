<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = \App\Models\User::factory()->create();
        $documentable = null;

        if ($user->role === 'professor') {
            $documentable = \App\Models\Professor::factory()->create(['user_id' => $user->id]);
        } elseif ($user->role === 'student') {
            $documentable = \App\Models\Student::factory()->create(['user_id' => $user->id]);
        }

        return [
            'user_id' => $user->id,
            'documentable_type' => $documentable ? get_class($documentable) : null,
            'documentable_id' => $documentable ? $documentable->id : null,
            'title' => fake()->sentence(),
            'file_path' => 'documents/'.fake()->uuid().'.pdf',
            'file_name' => fake()->word().'.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(1000, 10000000),
            'type' => fake()->randomElement(['CV', 'Thesis', 'Report', 'Certificate']),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
