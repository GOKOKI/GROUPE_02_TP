<?php

use App\Models\Student;
use App\Models\ThesisDefenseReport;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AttribuerNoteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test l'attribution d'une note via l'API.
     */
    public function test_attribuer_note(){

         $user = User::factory()->create();
    $this->actingAs($user);

    // Créer un étudiant et un rapport pour respecter les foreign keys
    $student = Student::factory()->create();
    $report = ThesisDefenseReport::factory()->create(['student_id' => $student->id]);

    $response = $this->post('attribuer-note', [
        'note' => 15.5,
        'thesis_defense_report_id' => $report->id,
        'student_id' => $student->id,
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('audit', [
        'student_id' => $student->id,
        'note' => 15.5,
        'thesis_defense_report_id' => $report->id,
    ]);

    }
}
