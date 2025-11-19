<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Document;
use App\Models\Professor;
use App\Models\Student;
use App\Models\ThesisDefenseReport;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $departments = Department::factory(3)->create();

        User::factory()->admin()->withoutTwoFactor()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $professorUsers = User::factory(5)->professor()->withoutTwoFactor()->create();

        $studentUsers = User::factory(10)->student()->withoutTwoFactor()->create();

        foreach ($studentUsers as $user) {
            Student::factory()->create([
                'user_id' => $user->id,
                'department_id' => $departments->random()->id,
            ]);
        }

        ThesisDefenseReport::factory(5)->create();

        Document::factory(10)->create();

        $testStudent = User::factory()->student()->withoutTwoFactor()->create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
        ]);

        Student::factory()->create([
            'user_id' => $testStudent->id,
            'department_id' => $departments->random()->id,
        ]);

        $testProfessor = User::factory()->professor()->withoutTwoFactor()->create([
            'name' => 'Test Professor',
            'email' => 'professor@example.com',
        ]);

        Professor::factory()->create([
            'user_id' => $testProfessor->id,
            'department_id' => $departments->random()->id,
        ]);
    }
}
