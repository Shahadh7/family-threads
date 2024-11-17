<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\MemoryItemController;
use App\Http\Controllers\FamilyTreeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TimeThreadController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/memory-item', [MemoryItemController::class, 'index'])->name('memory-items.index');
    Route::get('/memory-item/create', [MemoryItemController::class, 'create'])->name('memory-items.create');
    // Route::post('/memory-item', [MemoryItemController::class, 'store'])->name('memory-items.store');
    Route::get('/memory-item/{id}', [MemoryItemController::class, 'show'])->name('memory-items.show');
    // Route::get('/memory-item/{id}/edit', [MemoryItemController::class, 'edit'])->name('memory-items.edit');
    // Route::put('/memory-item/{id}', [MemoryItemController::class, 'update'])->name('memory-items.update');
    // Route::delete('/memory-item/{id}', [MemoryItemController::class, 'destroy'])->name('memory-items.destroy');

    Route::get('/family-tree', [FamilyTreeController::class, 'index'])->name('family-tree.index');
    Route::post('/family-tree', [FamilyTreeController::class, 'store'])->name('family-tree.store');
    Route::put('/family-tree', [FamilyTreeController::class, 'update'])->name('family-tree.update');

    Route::get('/get-users', [UserController::class, 'getAllUsers'])->name('get-users');
    Route::get('/current-user', [UserController::class, 'getCurrentUser'])->name('current-user');

    Route::get('/time-thread', [TimeThreadController::class, 'index'])->name('time-thread.index');
});

Route::get('/', [ConnectionController::class, 'show']);

require __DIR__.'/auth.php';
