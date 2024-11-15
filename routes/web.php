<?php

use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;


Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/event/{event}', [HomeController::class, 'show'])
    ->name('site.events.show');

Route::get('/events/export-upcoming', [ExportController::class, 'exportUpcomingEvents'])
    ->name('site.event.upcoming.export');

Route::middleware('auth')->group(function () {

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

        Route::get('/attendees', [AttendeeController::class, 'index'])
        ->name('attendees.index');

        Route::get('/events/{event}/export', [ExportController::class, 'exportEventWithRevenueData'])
        ->name('events.export');

    });

});
