<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/departments/create')
            ->assertStatus(200);
    }

    public function test_student_cannot_access_admin_routes(): void
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)
            ->get('/departments/create')
            ->assertStatus(403);
    }

    public function test_guest_cannot_access_protected_routes(): void
    {
        $this->get('/departments')
            ->assertRedirect('/login');
    }
}
