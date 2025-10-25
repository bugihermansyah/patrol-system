<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartPatrolSessionRequest;
use App\Http\Requests\StopPatrolSessionRequest;
use App\Models\PatrolSession;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PatrolSessionController extends Controller
{
    public function start(StartPatrolSessionRequest $req)
    {
        $user = $req->user();

        //idemponten: jika reques yang sama pernah diproses, kembalikan hasil yang sama
        if ($existing = PatrolSession::where('client_start_id', $req->client_start_id)->where('user_id', $user->id)->first()){
            return response()->json(['data' => $existing], Response::HTTP_OK);
        }

        return DB::transaction(function() use ($req, $user) {
            //Cegah double start
            $active = PatrolSession::activeFor($user->id)->lockForUpdate()->first();
            if ($active) {
                return response()->json([
                    'message'    => 'Patrol session masih aktif',
                    'data' => $active ,
                ], Response::HTTP_CONFLICT);
            }

            $patrol = PatrolSession::create([
                'user_id'        => $user->id,
                'shift_session_id'       => $req->shift_session_id,
                'status'         => 'active',
                'start_at'       => now(),
                'start_lat'      => $req->start_lat,
                'start_lon'      => $req->start_lon,
                'client_start_id'=> $req->client_start_id,
            ]);

            return response()->json(['data' => $patrol], Response::HTTP_CREATED);
        });
    }

    public function stop(StopPatrolSessionRequest $req)
    {
        $user = $req->user();

        //idemponten: jika reques yang sama pernah diproses, kembalikan hasil yang sama
        if ($existing = PatrolSession::where('client_end_id', $req->client_end_id)
                ->where('user_id', $user->id)
                ->first()){
            return response()->json(['data' => $existing], Response::HTTP_OK);
        }

        return DB::transaction(function() use ($req, $user) {
            // $patrol = PatrolSession::activeFor($user->id)->lockForUpdate()->first();
            $patrol = PatrolSession::activeFor($user->id)->lockForUpdate()->first();
            if (!$patrol) {
                return response()->json([
                    'message'    => 'Tidak ada sesi patrol aktif.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $patrol->update([
                'status'        => 'ended',
                'end_at'       => now(),
                'end_lat'      => $req->end_lat,
                'end_lon'      => $req->end_lon,
                'client_end_id'=> $req->client_end_id,
            ]);

            return response()->json(['data' => $patrol], Response::HTTP_OK);
        });
    }
}
