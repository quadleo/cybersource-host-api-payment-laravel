<?php

use App\Http\Controllers\CSPaymentHostController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/',[HomeController::class, 'index'])->name('home');
Route::get('/api-pay',[HomeController::class, 'apiIndex'])->name('api-index');
Route::post('/api-pay',[HomeController::class, 'apiPost'])->name('api-post');
Route::get('/api-auth-setup-reply',[HomeController::class, 'apiAuthSetupReply'])->name('api-auth-setup-reply');
Route::get('/api-auth-setup-url',[HomeController::class, 'apiAuthSetupUrl'])->name('api-auth-setup-url');

Route::group(['prefix' => 'webhook'], function () {    
    Route::any('/confirm-api-pay-redirect',[HomeController::class, 'apiConfirmPayRedirect'])->name('api-payment-redirect');
});

Route::get('/hosted-pay',[CSPaymentHostController::class, 'index'])->name('hosted-pay');
Route::post('/confirm-pay',[CSPaymentHostController::class, 'confirm'])->name('confirm-pay');
