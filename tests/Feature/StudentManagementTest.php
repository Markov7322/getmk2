<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_can_add_student_and_search(): void
    {
        $author = User::factory()->create(['role' => UserRole::AUTHOR]);
        $student = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $author->id]);

        $response = $this->actingAs($author)->post("/students/{$course->id}", [
            'identifier' => $student->email,
        ]);
        $response->assertRedirect();

        $this->assertDatabaseHas('course_user', [
            'course_id' => $course->id,
            'user_id' => $student->id,
        ]);

        $response = $this->actingAs($author)->get('/students?email=' . $student->email);
        $response->assertOk();
        $response->assertSee($course->title);
    }
}
