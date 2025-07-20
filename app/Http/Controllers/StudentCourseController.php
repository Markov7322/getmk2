<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentCourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = $request->user()->courses()->with('modules.lessons')->get();

        return Inertia::render('Courses/Enrolled', [
            'courses' => $courses,
        ]);
    }
}
