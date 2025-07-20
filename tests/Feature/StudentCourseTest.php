<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentCourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_enrolled_courses(): void
    {
        $student = User::factory()->create();
        $course = Course::factory()->create();
        $course->students()->attach($student);

        $response = $this->actingAs($student)->get('/my-courses');

        $response->assertOk();
        $response->assertSee($course->title);
    }
}
