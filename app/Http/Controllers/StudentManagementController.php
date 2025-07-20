<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Enums\UserRole;
use Inertia\Inertia;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $auth = $request->user();

        if (! in_array($auth->role, [UserRole::ADMIN, UserRole::AUTHOR])) {
            abort(403);
        }

        $coursesQuery = Course::query();
        if ($auth->role === UserRole::AUTHOR) {
            $coursesQuery->where('user_id', $auth->id);
        }
        $courses = $coursesQuery->get();

        $selectedId = $request->input('course_id');
        $selectedCourse = null;
        if ($selectedId) {
            $selectedCourse = Course::with('students')->find($selectedId);
        } elseif ($courses->count()) {
            $selectedCourse = Course::with('students')->find($courses->first()->id);
            $selectedId = $selectedCourse?->id;
        }

        $searchEmail = $request->input('email');
        $searchedCourses = [];
        if ($searchEmail) {
            $student = User::where('email', $searchEmail)->first();
            if ($student) {
                $scQuery = $student->courses();
                if ($auth->role === UserRole::AUTHOR) {
                    $scQuery->where('courses.user_id', $auth->id);
                }
                $searchedCourses = $scQuery->with('author')->get();
            }
        }

        return Inertia::render('Students/Index', [
            'courses' => $courses,
            'selectedCourse' => $selectedCourse,
            'selectedId' => $selectedId,
            'searchEmail' => $searchEmail,
            'searchedCourses' => $searchedCourses,
        ]);
    }

    public function store(Request $request, Course $course)
    {
        $auth = $request->user();

        if (! ($auth->role === UserRole::ADMIN || $auth->id === $course->user_id)) {
            abort(403);
        }

        $data = $request->validate([
            'identifier' => 'required|string',
        ]);

        $student = User::where('id', $data['identifier'])
            ->orWhere('email', $data['identifier'])->firstOrFail();

        $course->students()->syncWithoutDetaching([$student->id]);

        return Redirect::back();
    }
}
