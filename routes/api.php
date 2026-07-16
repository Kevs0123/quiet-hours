<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BookingApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public auth routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/verify-code', [AuthApiController::class, 'verifyCode']);
Route::post('/resend-code', [AuthApiController::class, 'resendCode']);
Route::post('/login', [AuthApiController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Authenticated routes (Sanctum token required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);

    Route::get('/bookings', [BookingApiController::class, 'index']);
    Route::post('/bookings', [BookingApiController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingApiController::class, 'show']);
    Route::put('/bookings/{booking}', [BookingApiController::class, 'update']);
    Route::delete('/bookings/{booking}', [BookingApiController::class, 'destroy']);
    Route::post('/bookings/{booking}/send-summary', [BookingApiController::class, 'sendSummary']);
});
