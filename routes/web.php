<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\CourseEnrollmentController;
use App\Http\Controllers\StudentManagementController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

    Route::get('/my-courses', [StudentCourseController::class, 'index'])->name('student.courses.index');

    Route::middleware('role:admin,author')->group(function () {
        Route::get('/cabinet', [CourseController::class, 'manage'])->name('courses.manage');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

        Route::get('/students', [StudentManagementController::class, 'index'])->name('students.index');
        Route::post('/students/{course}', [StudentManagementController::class, 'store'])->name('students.store');

        Route::get('/courses/{course}/students', [CourseEnrollmentController::class, 'index'])->name('courses.students.index');
        Route::post('/courses/{course}/students', [CourseEnrollmentController::class, 'store'])->name('courses.students.store');
        Route::delete('/courses/{course}/students/{user}', [CourseEnrollmentController::class, 'destroy'])->name('courses.students.destroy');
    });

    Route::middleware('role:admin,moderator,author')->group(function () {
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    });

    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
});

require __DIR__.'/auth.php';
