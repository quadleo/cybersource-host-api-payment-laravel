<?php

use App\Http\Controllers\Api\CSPaymentController;
use App\Http\Controllers\Api\CSVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/* cybersource */
Route::group(['prefix' => 'cybsersource'], function () {
    Route::post('checkout', [CSPaymentController::class, 'postCheckout']);
    Route::post('auth-setup', [CSVerificationController::class, 'authSetup']);
    Route::post('authentication', [CSVerificationController::class, 'authVerification']);
    Route::post('make-payment', [CSPaymentController::class, 'makePayment']);
    
});