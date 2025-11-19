<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\JuryMember;
use App\Models\Professor;
use App\Models\Student;
use App\Models\ThesisDefenseReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThesisDefenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_professor_can_create_thesis_defense(): void
    {
        $department = Department::factory()->create();
        $student = Student::factory()->create(['department_id' => $department->id]);
        $supervisor = Professor::factory()->create(['department_id' => $department->id]);

        $this->actingAs($supervisor->user)
            ->post('/thesis-defense-reports', [
                'student_id' => $student->id,
                'supervisor_id' => $supervisor->id,
                'title' => 'Test Thesis',
                'abstract' => 'Test abstract',
                'defense_date' => now()->addDays(7)->format('Y-m-d'),
                'defense_time' => '10:00',
                'room' => 'Room 101',
            ])
            ->assertRedirect('/thesis-defense-reports');

        $this->assertDatabaseHas('thesis_defense_reports', [
            'student_id' => $student->id,
            'supervisor_id' => $supervisor->id,
            'title' => 'Test Thesis',
            'room' => 'Room 101',
        ]);
    }

    public function test_supervisor_cannot_be_same_as_student(): void
    {
        $department = Department::factory()->create();
        $student = Student::factory()->create(['department_id' => $department->id]);
        $supervisor = Professor::factory()->create(['department_id' => $department->id]);

        // Make them the same user for this test
        $student->user_id = $supervisor->user_id;
        $student->save();

        $this->actingAs($supervisor->user)
            ->post('/thesis-defense-reports', [
                'student_id' => $student->id,
                'supervisor_id' => $supervisor->id,
                'title' => 'Test Thesis',
                'defense_date' => now()->addDays(7)->format('Y-m-d'),
                'defense_time' => '10:00',
            ])
            ->assertSessionHasErrors('supervisor_id');
    }

    public function test_student_can_view_own_thesis_defense(): void
    {
        $department = Department::factory()->create();
        $student = Student::factory()->create(['department_id' => $department->id]);
        $supervisor = Professor::factory()->create(['department_id' => $department->id]);
        $thesis = ThesisDefenseReport::factory()->create([
            'student_id' => $student->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $this->actingAs($student->user)
            ->get("/thesis-defense-reports/{$thesis->id}")
            ->assertStatus(200);
    }

    public function test_supervisor_can_view_supervised_thesis(): void
    {
        $department = Department::factory()->create();
        $student = Student::factory()->create(['department_id' => $department->id]);
        $supervisor = Professor::factory()->create(['department_id' => $department->id]);
        $thesis = ThesisDefenseReport::factory()->create([
            'student_id' => $student->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $this->actingAs($supervisor->user)
            ->get("/thesis-defense-reports/{$thesis->id}")
            ->assertStatus(200);
    }

    public function test_jury_member_can_view_assigned_thesis(): void
    {
        $department = Department::factory()->create();
        $student = Student::factory()->create(['department_id' => $department->id]);
        $supervisor = Professor::factory()->create(['department_id' => $department->id]);
        $juryProfessor = Professor::factory()->create(['department_id' => $department->id]);
        $thesis = ThesisDefenseReport::factory()->create([
            'student_id' => $student->id,
            'supervisor_id' => $supervisor->id,
        ]);

        JuryMember::factory()->create([
            'thesis_defense_report_id' => $thesis->id,
            'professor_id' => $juryProfessor->id,
        ]);

        $this->actingAs($juryProfessor->user)
            ->get("/thesis-defense-reports/{$thesis->id}")
            ->assertStatus(200);
    }

    public function test_admin_can_view_all_thesis_defenses(): void
    {
        $admin = User::factory()->admin()->create();
        $department = Department::factory()->create();
        $student = Student::factory()->create(['department_id' => $department->id]);
        $supervisor = Professor::factory()->create(['department_id' => $department->id]);
        $thesis = ThesisDefenseReport::factory()->create([
            'student_id' => $student->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $this->actingAs($admin)
            ->get("/thesis-defense-reports/{$thesis->id}")
            ->assertStatus(200);
    }

    public function test_supervisor_can_add_jury_member(): void
    {
        $department = Department::factory()->create();
        $student = Student::factory()->create(['department_id' => $department->id]);
        $supervisor = Professor::factory()->create(['department_id' => $department->id]);
        $juryProfessor = Professor::factory()->create(['department_id' => $department->id]);
        $thesis = ThesisDefenseReport::factory()->create([
            'student_id' => $student->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $this->actingAs($supervisor->user)
            ->post('/jury-members', [
                'thesis_defense_report_id' => $thesis->id,
                'professor_id' => $juryProfessor->id,
                'role' => 'member',
            ])
            ->assertRedirect("/thesis-defense-reports/{$thesis->id}");

        $this->assertDatabaseHas('jury_members', [
            'thesis_defense_report_id' => $thesis->id,
            'professor_id' => $juryProfessor->id,
            'role' => 'member',
        ]);
    }

    public function test_supervisor_cannot_be_jury_member(): void
    {
        $department = Department::factory()->create();
        $student = Student::factory()->create(['department_id' => $department->id]);
        $supervisor = Professor::factory()->create(['department_id' => $department->id]);
        $thesis = ThesisDefenseReport::factory()->create([
            'student_id' => $student->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $this->actingAs($supervisor->user)
            ->post('/jury-members', [
                'thesis_defense_report_id' => $thesis->id,
                'professor_id' => $supervisor->id, // Trying to add supervisor as jury member
                'role' => 'president',
            ])
            ->assertSessionHasErrors('professor_id');
    }
}
