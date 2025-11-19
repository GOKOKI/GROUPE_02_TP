<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Document;
use App\Models\Professor;
use App\Models\Student;
use App\Models\ThesisDefenseReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EndToEndTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_thesis_defense_workflow(): void
    {
        $department = Department::factory()->create();

        $admin = User::factory()->admin()->create();

        $professorUser = User::factory()->professor()->create();
        $professor = Professor::factory()->create([
            'user_id' => $professorUser->id,
            'department_id' => $department->id,
        ]);

        $studentUser = User::factory()->student()->create();
        $student = Student::factory()->create([
            'user_id' => $studentUser->id,
            'department_id' => $department->id,
        ]);


        $this->actingAs($professorUser)
            ->post('/thesis-defense-reports', [
                'student_id' => $student->id,
                'supervisor_id' => $professor->id,
                'title' => 'Advanced Machine Learning Techniques',
                'abstract' => 'This thesis explores advanced machine learning techniques...',
                'defense_date' => now()->addDays(30)->format('Y-m-d'),
                'defense_time' => '14:00',
                'room' => 'Auditorium A',
            ])
            ->assertRedirect('/thesis-defense-reports');

        $defense = ThesisDefenseReport::first();
        $this->assertNotNull($defense);
        $this->assertEquals('Advanced Machine Learning Techniques', $defense->title);
        $this->assertEquals('scheduled', $defense->status);

        $juryProfessor1 = Professor::factory()->create(['department_id' => $department->id]);
        $juryProfessor2 = Professor::factory()->create(['department_id' => $department->id]);

        $this->actingAs($professorUser)
            ->post('/jury-members', [
                'thesis_defense_report_id' => $defense->id,
                'professor_id' => $juryProfessor1->id,
                'role' => 'president',
            ])
            ->assertRedirect("/thesis-defense-reports/{$defense->id}");

        $this->actingAs($professorUser)
            ->post('/jury-members', [
                'thesis_defense_report_id' => $defense->id,
                'professor_id' => $juryProfessor2->id,
                'role' => 'secretary',
            ])
            ->assertRedirect("/thesis-defense-reports/{$defense->id}");

        $this->assertEquals(2, $defense->fresh()->juryMembers->count());

        $this->actingAs($studentUser)
            ->post('/documents', [
                'title' => 'Thesis Manuscript',
                'file' => UploadedFile::fake()->create('thesis.pdf', 2000),
                'documentable_type' => 'App\\Models\\Student',
                'documentable_id' => $student->id,
                'type' => 'Thesis',
                'description' => 'Final thesis manuscript',
            ])
            ->assertRedirect('/documents');

        $document = Document::first();
        $this->assertNotNull($document);
        $this->assertEquals('Thesis Manuscript', $document->title);
        $this->assertEquals('Thesis', $document->type);

        $this->actingAs($studentUser)
            ->get("/thesis-defense-reports/{$defense->id}")
            ->assertStatus(200);

        $this->actingAs($professorUser)
            ->get("/thesis-defense-reports/{$defense->id}")
            ->assertStatus(200);

        $this->actingAs($juryProfessor1->user)
            ->get("/thesis-defense-reports/{$defense->id}")
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get("/thesis-defense-reports/{$defense->id}")
            ->assertStatus(200);

        $this->actingAs($professorUser)
            ->put("/thesis-defense-reports/{$defense->id}", [
                'student_id' => $student->id,
                'supervisor_id' => $professor->id,
                'title' => 'Advanced Machine Learning Techniques',
                'abstract' => 'This thesis explores advanced machine learning techniques...',
                'defense_date' => now()->addDays(30)->format('Y-m-d'),
                'defense_time' => '14:00',
                'room' => 'Auditorium A',
                'final_grade' => 16.5,
                'status' => 'completed',
                'comments' => 'Excellent work on machine learning applications.',
            ])
            ->assertRedirect('/thesis-defense-reports');

        $defense->refresh();
        $this->assertEquals('completed', $defense->status);
        $this->assertEquals(16.5, $defense->final_grade);
        $this->assertEquals('Excellent work on machine learning applications.', $defense->comments);

        $this->assertDatabaseHas('thesis_defense_reports', [
            'id' => $defense->id,
            'status' => 'completed',
            'final_grade' => 16.5,
        ]);

        $this->assertDatabaseHas('jury_members', [
            'thesis_defense_report_id' => $defense->id,
            'role' => 'president',
        ]);

        $this->assertDatabaseHas('jury_members', [
            'thesis_defense_report_id' => $defense->id,
            'role' => 'secretary',
        ]);

        $this->assertDatabaseHas('documents', [
            'title' => 'Thesis Manuscript',
            'type' => 'Thesis',
        ]);
    }

    public function test_admin_system_management_workflow(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/departments', [
                'name' => 'Computer Science',
                'code' => 'CS',
                'description' => 'Department of Computer Science and Engineering',
            ])
            ->assertRedirect('/departments');

        $department = Department::first();
        $this->assertNotNull($department);
        $this->assertEquals('Computer Science', $department->name);

        $this->actingAs($admin)
            ->post('/professors', [
                'name' => 'Dr. John Smith',
                'email' => 'john.smith@university.edu',
                'password' => 'password123',
                'department_id' => $department->id,
                'title' => 'Professor',
                'specialty' => 'Artificial Intelligence',
                'phone' => '+1234567890',
                'bio' => 'Expert in AI and machine learning',
            ])
            ->assertRedirect('/professors');

        $professor = Professor::first();
        $this->assertNotNull($professor);
        $this->assertEquals('Dr. John Smith', $professor->user->name);
        $this->assertEquals('Professor', $professor->title);

        $this->actingAs($admin)
            ->post('/students', [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@student.edu',
                'password' => 'password123',
                'department_id' => $department->id,
                'student_number' => 'STU2024001',
                'phone' => '+0987654321',
                'enrollment_date' => '2024-09-01',
                'level' => 'Master',
            ])
            ->assertRedirect('/students');

        $student = Student::first();
        $this->assertNotNull($student);
        $this->assertEquals('Alice Johnson', $student->user->name);
        $this->assertEquals('STU2024001', $student->student_number);

        $this->actingAs($admin)
            ->post('/system-settings', [
                'key' => 'max_file_size',
                'value' => '10485760',
                'type' => 'integer',
                'description' => 'Maximum allowed file size in bytes',
            ])
            ->assertRedirect('/system-settings');

        $setting = \App\Models\SystemSetting::first();
        $this->assertNotNull($setting);
        $this->assertEquals('max_file_size', $setting->key);
        $this->assertEquals(10485760, $setting->value);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200);

        $this->assertEquals(1, Department::count());
        $this->assertEquals(1, Professor::count());
        $this->assertEquals(1, Student::count());
        $this->assertEquals(1, \App\Models\SystemSetting::count());
    }
}
