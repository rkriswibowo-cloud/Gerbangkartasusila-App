<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstallationTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_database_can_be_created_from_migrations(): void
    {
        $this->seed();
        $this->getJson('/api/destinations')->assertOk()->assertJsonPath('data.total', 16);
        $this->getJson('/api/destinations?page=2')->assertOk()->assertJsonCount(6, 'data.data');
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_admin_creation_command_and_admin_pages(): void
    {
        $this->get('/admin/login')->assertOk();
        $this->artisan('app:create-admin')
            ->expectsQuestion('Name', 'Test Admin')
            ->expectsQuestion('Email', 'admin@example.test')
            ->expectsQuestion('Password (at least 12 characters)', 'unique-test-password-123')
            ->expectsOutput('Administrator created. Sign in at /admin.')
            ->assertSuccessful();
        $user = \App\Models\User::where('email', 'admin@example.test')->firstOrFail();
        $this->assertTrue($user->hasRole('super_admin'));
        $this->actingAs($user)->get('/admin')->assertOk();
        $this->actingAs($user)->get('/admin/tickets')->assertOk();
    }
}
