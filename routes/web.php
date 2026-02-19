<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Nested Resource Routes
Route::resource('projects', ProjectController::class)->scoped();
Route::resource('projects.tasks', TaskController::class)->scoped();
Route::resource('projects.tasks.entries', EntryController::class)->scoped();

// Additional CRUD and Custom Routes
Route::resource('users', UserController::class);

// Scoped Routes (Project, Task, and Statistics Management)
Route::scopeBindings()->group(function () {
    // --- Project User Management ---
    Route::post('projects/{project}/users', [UserController::class, 'attachToProject'])
        ->name('projects.users.store');
    Route::put('projects/{project}/users/{user}', [UserController::class, 'updateProjectRole'])
        ->name('projects.users.update');
    Route::delete('projects/{project}/users/{user}', [UserController::class, 'detachFromProject'])
        ->name('projects.users.destroy');

    // --- Task Management (State Changes) ---
    Route::get('projects/{project}/tasks/{task}/complete', [TaskController::class, 'complete'])
        ->name('projects.tasks.complete');
    Route::get('projects/{project}/tasks/{task}/archived', [TaskController::class, 'archived'])
        ->name('projects.tasks.archived');

    // --- Project Management (State Changes) ---
    Route::get('projects/{project}/archive', [ProjectController::class, 'archive'])
        ->name('projects.archive');
    Route::get('projects/{project}/restore', [ProjectController::class, 'restore'])
        ->name('projects.restore');

    // --- User Search ---
    Route::get('users/search', [UserController::class, 'search'])
        ->name('users.search');

    // --- Statistics & Export ---
    Route::middleware('auth')->get('projects/{project}/statistics', [ProjectController::class, 'statistics'])
        ->name('projects.statistics');
    Route::get('projects/{project}/statistics/export', [ProjectController::class, 'export'])
        ->name('projects.statistics.export');
});

require __DIR__.'/auth.php';