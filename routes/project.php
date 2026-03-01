<?php

use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectCostController;
use App\Http\Controllers\Project\ProjectMemberController;
use App\Http\Controllers\Project\ProjectTaskController;
use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 Project Management Routes
// ==========================
Route::prefix('projects')->name('projects.')->middleware(['auth', 'check.expired'])->group(function () {

    // Project Routes
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/api/data', [ProjectController::class, 'data'])->name('data');
    Route::get('/create', [ProjectController::class, 'create'])->name('create');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
    Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
    Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');

    // Project Kanban Board
    Route::get('/{project}/kanban', [ProjectController::class, 'kanban'])->name('kanban');

    // Project Task Routes
    Route::prefix('{project}/tasks')->name('tasks.')->group(function () {
        Route::get('/', [ProjectTaskController::class, 'index'])->name('index');
        Route::get('/api/data', [ProjectTaskController::class, 'data'])->name('data');
        Route::get('/api/kanban', [ProjectTaskController::class, 'kanbanData'])->name('kanban-data');
        Route::post('/', [ProjectTaskController::class, 'store'])->name('store');

        // Task move endpoint (Kanban)
        Route::post('/{task}/move', [ProjectTaskController::class, 'move'])->name('move');

        // Task edit modal
        Route::get('/create-modal', [ProjectTaskController::class, 'editModal'])->name('create-modal');
        Route::get('/{task}/edit-modal', [ProjectTaskController::class, 'editModal'])->name('edit-modal');
        Route::get('/{task}/edit', [ProjectTaskController::class, 'editModal'])->name('edit');

        // Task update/delete
        Route::put('/{task}', [ProjectTaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [ProjectTaskController::class, 'destroy'])->name('destroy');
    });

    // Project Member Routes
    Route::prefix('{project}/members')->name('members.')->group(function () {
        Route::post('/', [ProjectMemberController::class, 'store'])->name('store');
        Route::get('/create-modal', [ProjectMemberController::class, 'editModal'])->name('create-modal');
        Route::get('/{member}/edit-modal', [ProjectMemberController::class, 'editModal'])->name('edit-modal');
        Route::delete('/{member}', [ProjectMemberController::class, 'destroy'])->name('destroy');
    });

    // Project Cost Routes
    Route::prefix('{project}/costs')->name('costs.')->group(function () {
        Route::post('/', [ProjectCostController::class, 'store'])->name('store');
        Route::get('/create-modal', [ProjectCostController::class, 'editModal'])->name('create-modal');
        Route::get('/{cost}/edit-modal', [ProjectCostController::class, 'editModal'])->name('edit-modal');
        Route::put('/{cost}', [ProjectCostController::class, 'update'])->name('update');
        Route::delete('/{cost}', [ProjectCostController::class, 'destroy'])->name('destroy');
    });

});
