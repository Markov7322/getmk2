<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Enums\UserRole;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (in_array($user->role, [UserRole::ADMIN, UserRole::MODERATOR])) {
            $courses = Course::all();
        } elseif ($user->role === UserRole::AUTHOR) {
            $courses = Course::where('user_id', $user->id)->get();
        } else {
            $courses = Course::all();
        }

        return response()->json($courses);
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
        ]);

        $course = Course::create(array_merge($validated, ['user_id' => $user->id]));

        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::findOrFail($id);
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
        ]);

        $course->update($validated);

        return response()->json($course);
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
