<?php

use App\Http\Controllers\TimesheetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Ramsey\Uuid\Type\Time;

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
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::controller(TimesheetController::class)->group(function () {
        Route::get('/timesheet', 'index')->name('timesheet');
        Route::get('/timesheets/list', 'list')->name('timesheets.list');
    });
});
