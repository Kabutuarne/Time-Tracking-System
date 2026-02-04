<?php

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// nested resource routes
Route::resource('projects', ProjectController::class)->scoped();
Route::resource('projects.tasks', TaskController::class)->scoped();
Route::resource('projects.tasks.entries', EntryController::class)->scoped();

// routes/web.php

Route::scopeBindings()->group(function () {
    // Project user management
    Route::post(
        'projects/{project}/users',
        [UserController::class, 'attachToProject']
    )->name('projects.users.store');

    Route::put(
        'projects/{project}/users/{user}',
        [UserController::class, 'updateProjectRole']
    )->name('projects.users.update');

    Route::delete(
        'projects/{project}/users/{user}',
        [UserController::class, 'detachFromProject']
    )->name('projects.users.destroy');

    // Task management
    Route::get('projects/{project}/tasks/{task}/complete', [TaskController::class, 'complete'])
        ->name('projects.tasks.complete');
    Route::get('projects/{project}/tasks/{task}/archived', [TaskController::class, 'archived'])
        ->name('projects.tasks.archived');

    // User search route
    Route::get('/users/search', function () {
    $q = request('q', '');
    return User::query()
        ->where('username', 'like', "%{$q}%") //finds by username
        ->orWhere('email', 'like', "%{$q}%") // or by email
        ->limit(10)
        ->get(['id','username','email']);
        });
});
Route::resource('users', UserController::class);
// ->middleware('auth');
require __DIR__.'/auth.php';