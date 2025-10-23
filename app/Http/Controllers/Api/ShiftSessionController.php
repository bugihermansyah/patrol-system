<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\ShiftSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftSessionController extends Controller
{
    public function start(Request $request)
    {
        $user = $request->user();

        $v = Validator::make($request->all(), [
            'shift_id' => 'required|exists:shifts,id',
        ]);
        if ($v->fails()) {
            return response()->json(['message' => 'Invalid data', 'errors' => $v->errors()], 422);
        }

        // Cegah double start
        $active = ShiftSession::where('user_id', $user->id)->whereNull('ended_at')->first();
        if ($active) {
            return response()->json([
                'message'    => 'Shift masih aktif',
                'session_id' => $active->id,
                'started_at' => $active->started_at,
            ], 409);
        }

        $shift = Shift::findOrFail($request->shift_id);

        $session = ShiftSession::create([
            'user_id'   => $user->id,
            'shift_id'  => $shift->id,
            'started_at'=> now(),
        ]);

        return response()->json([
            'session_id' => $session->id,
            'started_at' => $session->started_at,
            'shift'      => [
                'id'         => $shift->id,
                'name'       => $shift->name,
                'start_time' => $shift->start_time,
                'end_time'   => $shift->end_time,
            ],
        ], 201);
    }

    public function end(Request $request)
    {
        $user = $request->user();

        $v = Validator::make($request->all(), [
            'session_id' => 'required|exists:shift_sessions,id',
        ]);
        if ($v->fails()) {
            return response()->json(['message' => 'Invalid data', 'errors' => $v->errors()], 422);
        }

        $session = ShiftSession::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$session) {
            return response()->json(['message' => 'Session tidak ditemukan'], 404);
        }
        if ($session->ended_at) {
            return response()->json(['message' => 'Session sudah berakhir'], 409);
        }

        $session->ended_at = now();
        $session->save();

        return response()->json([
            'message'   => 'Shift diakhiri',
            'session_id'=> $session->id,
            'ended_at'  => $session->ended_at,
        ], 200);
    }
}
