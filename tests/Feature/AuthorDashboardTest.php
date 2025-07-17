<?php

namespace Tests\Feature;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_cannot_access_dashboard(): void
    {
        $user = User::factory()->create(['role' => UserRole::AUTHOR]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertForbidden();
    }

    public function test_author_can_access_cabinet(): void
    {
        $user = User::factory()->create(['role' => UserRole::AUTHOR]);

        $response = $this->actingAs($user)->get('/cabinet');

        $response->assertOk();
    }
}
