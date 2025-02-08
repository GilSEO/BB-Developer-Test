<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageCapsuleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/message-capsules', [MessageCapsuleController::class, 'store']);
    Route::get('/message-capsules', [MessageCapsuleController::class, 'index']);
    Route::get('/message-capsules/{capsule}', [MessageCapsuleController::class, 'show']);
    Route::put('/message-capsules/{capsule}', [MessageCapsuleController::class, 'update']);
});
