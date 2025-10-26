<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckpointStoreRequest;
use App\Models\PatrolCheckpoint;
use App\Models\PatrolSession;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PatrolCheckpointController extends Controller
{
    use AuthorizesRequests;

    // Dengan route-model binding: /patrols/{patrol}/checkpoints
    public function store(CheckpointStoreRequest $req, PatrolSession $patrol)
    {
        // $this->authorize('view', $patrol);

        // if ($patrol->status !== 'active') {
        //     return response()->json(['message' => 'Patroli sudah tidak aktif.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        // }

        // Idempoten: kalau client_id sudah pernah dipakai, balikan data + foto
        if ($exists = PatrolCheckpoint::where('client_id', $req->client_id)->first()) {
            return response()->json([
                'data' => array_merge($exists->toArray(), ['photos' => $exists->photosPayload()])
            ], Response::HTTP_OK);
        }

        return DB::transaction(function () use ($req, $patrol) {
            $cp = PatrolCheckpoint::create([
                'patrol_session_id' => $patrol->id,
                'checkpoint_id'     => $req->checkpoint_id,
                'qr_scanned'        => $req->qr_scanned,
                'is_valid'          => (bool)($req->is_valid ?? true),
                'scanned_at'        => $req->scanned_at, // jika null, boleh isi now() sesuai kebutuhan
                'lat'               => $req->lat,
                'lon'               => $req->lon,
                'note'              => $req->note,
                'client_id'         => $req->client_id,
            ]);

            // === FOTO: multipart files ===
            if ($req->hasFile('photos')) {
                foreach ($req->file('photos') as $file) {
                    $cp->addMedia($file)->toMediaCollection('patrol_checkpoint_photos');
                }
            }

            // === FOTO: base64 (opsional) ===
            // if (is_array($req->photos_base64)) {
            //     foreach ($req->photos_base64 as $i => $b64) {
            //         // Kamu bisa tambah validasi mimetype sendiri di sini
            //         $cp->addMediaFromBase64($b64)
            //            ->usingFileName('cp_'.$cp->id.'_'.($i+1).'.jpg')
            //            ->toMediaCollection('photos');
            //     }
            // }

            return response()->json([
                'data' => array_merge($cp->toArray(), ['photos' => $cp->photosPayload()])
            ], Response::HTTP_CREATED);
        });
    }
}
