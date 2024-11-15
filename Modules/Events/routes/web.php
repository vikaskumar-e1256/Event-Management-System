<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Events\Http\Controllers\EventCommentController;
use Modules\Events\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/events/{event}/comments', [EventCommentController::class, 'index'])
    ->name('comments.index');

Route::middleware([AdminMiddleware::class])->group(function () {
    Route::resource('/events', EventController::class);
});

Route::middleware('auth')->group(function () {
    Route::post('/events/{event}/comments', [EventCommentController::class, 'store'])
    ->name('comments.store');
});
