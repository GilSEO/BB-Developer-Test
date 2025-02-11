<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageCapsuleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/user', function () {
    return response()->json(auth()->user());
})->middleware('auth:sanctum');

Route::post('/login', [AuthenticatedSessionController::class, 'apiLogin']);

Route::post('/logout', [AuthenticatedSessionController::class, 'apiLogout'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/message-capsules', [MessageCapsuleController::class, 'index']);
    Route::post('/message-capsules', [MessageCapsuleController::class, 'store']);
    Route::get('/message-capsules/{capsule}', [MessageCapsuleController::class, 'show']);
    Route::put('/message-capsules/{capsule}', [MessageCapsuleController::class, 'update']);
});
