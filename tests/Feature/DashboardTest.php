<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_authenticated_admin_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertRedirect('/admin/dashboard');
    }

    public function test_authenticated_professor_is_redirected_to_professor_dashboard(): void
    {
        $professor = User::factory()->professor()->create();

        $this->actingAs($professor)
            ->get('/dashboard')
            ->assertRedirect('/professor/dashboard');
    }

    public function test_authenticated_student_is_redirected_to_student_dashboard(): void
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)
            ->get('/dashboard')
            ->assertRedirect('/student/dashboard');
    }
}
