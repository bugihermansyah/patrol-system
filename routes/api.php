<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatrolController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\ShiftSessionController;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/patrols', [PatrolController::class, 'index']);
    Route::post('/patrols', [PatrolController::class, 'store']);
    Route::get('/patrols/{patrol}', [PatrolController::class, 'show']);
    Route::delete('/patrols/{patrol}', [PatrolController::class, 'destroy']);

    Route::get('/shifts', [ShiftController::class, 'index']);

    Route::post('/shift-sessions/start', [ShiftSessionController::class, 'start']);
    Route::post('/shift-sessions/end',   [ShiftSessionController::class, 'end']);
});
 