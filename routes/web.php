<?php

use App\Http\Controllers\EventsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [EventsController::class, 'index'])->name('dashboard');
    Route::post('/Create', [EventsController::class, 'store'])->name('events.store')->middleware([HandlePrecognitiveRequests::class]);
    Route::get('/editar-evento/{id}', [EventsController::class, 'edit'])->name('event.edit');
    Route::put('/editar-evento/{id}', [EventsController::class, 'update'])->name('event.update')->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('/delete-evento/{id}', [EventsController::class, 'destroy'])->name('event.destroy');
});
