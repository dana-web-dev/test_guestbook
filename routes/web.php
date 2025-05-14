<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
Route::get('/messages/create', [App\Http\Controllers\MessageController::class, 'create'])->name('messages.create');
Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
Route::get('/messages/{message}/edit', [App\Http\Controllers\MessageController::class, 'edit'])->name('messages.edit');
Route::post('/messages/{message}', [App\Http\Controllers\MessageController::class, 'update'])->name('messages.update');
Route::delete('/messages/{message}', [App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('messages', App\Http\Controllers\MessageController::class);

Route::middleware('auth')->group(function () {
    Route::get('users', [RegisteredUserController::class, 'index'])
        ->name('users.index');

    Route::get('users/{user}', [RegisteredUserController::class, 'edit'])->name('users.edit');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
