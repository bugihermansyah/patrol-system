<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatrolCheckpointController;
use App\Http\Controllers\Api\PatrolController;
use App\Http\Controllers\Api\PatrolSessionController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\ShiftSessionController;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Patrol Session
    Route::post('/patrols/start', [PatrolSessionController::class, 'start']);
    Route::post('/patrols/stop', [PatrolSessionController::class, 'stop']);

    // Shift
    Route::get('/shifts', [ShiftController::class, 'index']);

    // Shift Session
    Route::post('/shift-sessions/start', [ShiftSessionController::class, 'start']);
    Route::post('/shift-sessions/end',   [ShiftSessionController::class, 'end']);

    // Checkpoint
    Route::post('/patrols/{patrol}/checkpoints', [PatrolCheckpointController::class, 'store'])
        ->whereNumber('patrol');
});
 