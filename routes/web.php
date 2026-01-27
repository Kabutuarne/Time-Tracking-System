<?php

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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// nested resource routes
Route::resource('projects', ProjectController::class)->scoped();
Route::resource('projects.tasks', TaskController::class)->scoped();
Route::resource('projects.tasks.entries', EntryController::class)->scoped();
Route::resource('users', UserController::class);

Route::scopeBindings()->group(function () {
    // Mark task as completed
    Route::get('projects/{project}/tasks/{task}/complete', [TaskController::class, 'complete'])
        ->name('projects.tasks.complete');
    // Archive task
    Route::get('projects/{project}/tasks/{task}/archived', [TaskController::class, 'archived'])
        ->name('projects.tasks.archived');
});


// ->middleware('auth');

require __DIR__.'/auth.php';
