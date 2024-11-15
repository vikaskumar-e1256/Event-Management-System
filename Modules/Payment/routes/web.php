<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

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

Route::middleware('auth')->group(function () {
    Route::get('payments/result', [PaymentController::class, 'paymentResult'])
        ->name('payments.result');
    Route::post('payments/make', [PaymentController::class, 'makePayment'])
        ->name('payments.make');
});