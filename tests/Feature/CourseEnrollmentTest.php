<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_can_enroll_and_remove_student(): void
    {
        $author = User::factory()->create(['role' => UserRole::AUTHOR]);
        $student = User::factory()->create();
        $course = Course::factory()->create(['user_id' => $author->id]);

        $response = $this->actingAs($author)->post("/courses/{$course->id}/students", [
            'identifier' => $student->id,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('course_user', [
            'course_id' => $course->id,
            'user_id' => $student->id,
        ]);

        $response = $this->actingAs($author)->delete("/courses/{$course->id}/students/{$student->id}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('course_user', [
            'course_id' => $course->id,
            'user_id' => $student->id,
        ]);
    }
}
