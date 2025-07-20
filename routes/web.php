<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::resource('tasks', TaskController::class);

// Additional routes showcasing native PHP functions
Route::get('/tasks-export', [TaskController::class, 'export'])->name('tasks.export');
Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('tasks.dashboard');
Route::get('/tasks-search', [TaskController::class, 'search'])->name('tasks.search');
Route::get('/analytics', [TaskController::class, 'analytics'])->name('tasks.analytics');
Route::post('/tasks-bulk-update', [TaskController::class, 'bulkUpdate'])->name('tasks.bulk_update');
Route::get('/generate-report', [TaskController::class, 'generateReport'])->name('tasks.report');
