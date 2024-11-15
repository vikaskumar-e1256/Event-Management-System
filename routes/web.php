<?php

use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Exports\RevenueEventExport;
use App\Http\Controllers\EventCommentController;
use App\Http\Controllers\ExportController;
use Maatwebsite\Excel\Facades\Excel;


Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/event/{event}', [HomeController::class, 'show'])
    ->name('site.events.show');


Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register.form');
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login.form');
});

Route::post('/register', [AuthController::class, 'register'])
    ->name('register');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

Route::get('/events/{event}/comments', [EventCommentController::class, 'index'])
    ->name('comments.index');

Route::get('/events/export-upcoming', [ExportController::class, 'exportUpcomingEvents'])
    ->name('site.event.upcoming.export');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

    Route::get('payments/result', [PaymentController::class, 'paymentResult'])
    ->name('payments.result');
    Route::post('payments/make', [PaymentController::class, 'makePayment'])
    ->name('payments.make');

    Route::post('/events/{event}/comments', [EventCommentController::class, 'store'])
    ->name('comments.store');


    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
        Route::resource('/events', EventController::class);

        Route::get('/attendees', [AttendeeController::class, 'index'])
        ->name('attendees.index');

        Route::get('/events/{event}/export', [ExportController::class, 'exportEventWithRevenueData'])
        ->name('events.export');

    });

});
