<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

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
