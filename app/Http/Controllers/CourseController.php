<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Support\Arr;
use App\Enums\UserRole;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (in_array($user->role, [UserRole::ADMIN, UserRole::MODERATOR])) {
            $courses = Course::with('modules.lessons')->get();
        } elseif ($user->role === UserRole::AUTHOR) {
            $courses = Course::with('modules.lessons')->where('user_id', $user->id)->get();
        } else {
            $courses = Course::with('modules.lessons')->get();
        }

        return response()->json($courses);
    }

    public function manage(Request $request)
    {
        $user = $request->user();

        if (! in_array($user->role, [UserRole::ADMIN, UserRole::AUTHOR])) {
            abort(403);
        }

        $query = Course::with('modules.lessons');
        if ($user->role === UserRole::AUTHOR) {
            $query->where('user_id', $user->id);
        }

        $courses = $query->get();

        return Inertia::render('Courses/Index', [
            'courses' => $courses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        if (! in_array($user->role, [UserRole::ADMIN, UserRole::AUTHOR])) {
            abort(403);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (! in_array($user->role, [UserRole::ADMIN, UserRole::AUTHOR])) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'required|numeric',
            'modules' => 'array',
            'modules.*.title' => 'required|string',
            'modules.*.lessons' => 'array',
            'modules.*.lessons.*.title' => 'required|string',
        ]);

        $courseData = Arr::except($validated, 'modules');
        $course = Course::create(array_merge($courseData, ['user_id' => $user->id]));

        if (isset($validated['modules'])) {
            foreach ($validated['modules'] as $moduleData) {
                $module = $course->modules()->create(['title' => $moduleData['title']]);
                if (! empty($moduleData['lessons'])) {
                    foreach ($moduleData['lessons'] as $lessonData) {
                        $module->lessons()->create(['title' => $lessonData['title']]);
                    }
                }
            }
        }

        return response()->json($course->load('modules.lessons'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::with('modules.lessons')->findOrFail($id);
        return response()->json($course);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $course = Course::findOrFail($id);
        $user = request()->user();

        if ($user->role === UserRole::ADMIN ||
            $user->role === UserRole::MODERATOR ||
            ($user->role === UserRole::AUTHOR && $course->user_id == $user->id)) {
            return response()->json($course);
        }

        abort(403);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);
        $user = $request->user();

        if (! ($user->role === UserRole::ADMIN ||
            $user->role === UserRole::MODERATOR ||
            ($user->role === UserRole::AUTHOR && $course->user_id == $user->id))) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'modules' => 'array',
            'modules.*.title' => 'required|string',
            'modules.*.lessons' => 'array',
            'modules.*.lessons.*.title' => 'required|string',
        ]);

        $courseData = Arr::except($validated, 'modules');
        $course->update($courseData);

        if (isset($validated['modules'])) {
            // Reset modules and lessons
            $course->modules()->delete();
            foreach ($validated['modules'] as $moduleData) {
                $module = $course->modules()->create(['title' => $moduleData['title']]);
                if (! empty($moduleData['lessons'])) {
                    foreach ($moduleData['lessons'] as $lessonData) {
                        $module->lessons()->create(['title' => $lessonData['title']]);
                    }
                }
            }
        }

        return response()->json($course->load('modules.lessons'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $user = request()->user();

        if (! ($user->role === UserRole::ADMIN ||
            ($user->role === UserRole::AUTHOR && $course->user_id == $user->id))) {
            abort(403);
        }

        $course->delete();

        return response()->json(['status' => 'deleted']);
    }
}
