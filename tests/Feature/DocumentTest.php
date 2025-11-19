<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Document;
use App\Models\Professor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_professor_can_upload_document(): void
    {
        $department = Department::factory()->create();
        $professor = Professor::factory()->create(['department_id' => $department->id]);

        $this->actingAs($professor->user)
            ->post('/documents', [
                'title' => 'Test Document',
                'file' => UploadedFile::fake()->create('test.pdf', 1000),
                'documentable_type' => 'App\\Models\\Professor',
                'documentable_id' => $professor->id,
                'type' => 'CV',
                'description' => 'Test description',
            ])
            ->assertRedirect('/documents');

        $this->assertDatabaseHas('documents', [
            'title' => 'Test Document',
            'type' => 'CV',
            'description' => 'Test description',
            'documentable_type' => 'App\Models\Professor',
            'documentable_id' => $professor->id,
        ]);
    }

    public function test_student_can_view_own_documents(): void
    {
        $department = Department::factory()->create();
        $student = Student::factory()->create(['department_id' => $department->id]);
        $document = Document::factory()->create([
            'documentable_type' => 'App\Models\Student',
            'documentable_id' => $student->id,
        ]);

        $this->actingAs($student->user)
            ->get("/documents/{$document->id}")
            ->assertStatus(200);
    }

    public function test_student_cannot_view_other_students_documents(): void
    {
        $department = Department::factory()->create();
        $student1 = Student::factory()->create(['department_id' => $department->id]);
        $student2 = Student::factory()->create(['department_id' => $department->id]);
        $document = Document::factory()->create([
            'documentable_type' => 'App\Models\Student',
            'documentable_id' => $student2->id,
        ]);

        $this->actingAs($student1->user)
            ->get("/documents/{$document->id}")
            ->assertStatus(403);
    }

    public function test_admin_can_view_all_documents(): void
    {
        $admin = User::factory()->admin()->create();
        $department = Department::factory()->create();
        $student = Student::factory()->create(['department_id' => $department->id]);
        $document = Document::factory()->create([
            'documentable_type' => 'App\Models\Student',
            'documentable_id' => $student->id,
        ]);

        $this->actingAs($admin)
            ->get("/documents/{$document->id}")
            ->assertStatus(200);
    }

    public function test_document_file_is_stored_correctly(): void
    {
        $department = Department::factory()->create();
        $professor = Professor::factory()->create(['department_id' => $department->id]);

        $file = UploadedFile::fake()->create('test.pdf', 1000);

        $response = $this->actingAs($professor->user)
            ->post('/documents', [
                'title' => 'Test Document',
                'file' => $file,
                'documentable_type' => 'App\\Models\\Professor',
                'documentable_id' => $professor->id,
            ]);

        $response->assertRedirect('/documents');

        $document = Document::first();
        $this->assertNotNull($document, 'Document should be created');

        Storage::disk('public')->assertExists($document->file_path);
        $this->assertEquals($file->getClientOriginalName(), $document->file_name);
        $this->assertEquals($file->getMimeType(), $document->mime_type);
        $this->assertEquals($file->getSize(), $document->file_size);
    }
}
