<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CourseEnrollmentController extends Controller
{
    public function index(Request $request, Course $course)
    {
        $user = $request->user();

        if ($user->role !== \App\Enums\UserRole::ADMIN && $user->id !== $course->user_id) {
            abort(403);
        }

        return Inertia::render('Courses/Students', [
            'course' => $course->load('students'),
        ]);
    }

    public function store(Request $request, Course $course)
    {
        $user = $request->user();

        if ($user->role !== \App\Enums\UserRole::ADMIN && $user->id !== $course->user_id) {
            abort(403);
        }

        $data = $request->validate([
            'identifier' => 'required',
        ]);

        $student = User::where('id', $data['identifier'])
            ->orWhere('name', $data['identifier'])->firstOrFail();

        $course->students()->syncWithoutDetaching([$student->id]);

        return Redirect::back();
    }

    public function destroy(Request $request, Course $course, User $user)
    {
        $auth = $request->user();

        if ($auth->role !== \App\Enums\UserRole::ADMIN && $auth->id !== $course->user_id) {
            abort(403);
        }

        $course->students()->detach($user->id);

        return Redirect::back();
    }
}
