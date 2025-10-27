<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checkpoint;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckpointController extends Controller
{
    public function validateQr(string $code)
    {
        $cp = Checkpoint::where('qr_code', $code)->first();

        if (!$cp) {
            return response()->json([
                'valid'   => false,
                'reason'  => 'not_found',
                'message' => 'QR tidak terdaftar.',
            ], Response::HTTP_OK);
        }

        if (!$cp->is_active) {
            return response()->json([
                'valid'   => false,
                'reason'  => 'inactive',
                'message' => 'Checkpoint non-aktif.',
                'data'    => [
                    'id'   => $cp->id,
                    'name' => $cp->name,
                    'qr'   => $cp->qr_code,
                    'is_active' => (bool) $cp->is_active,
                ],
            ], Response::HTTP_OK);
        }

        return response()->json([
            'valid' => true,
            'data'  => [
                'id'   => $cp->id,
                'name' => $cp->name,
                'qr'   => $cp->qr_code,
                'is_active' => (bool) $cp->is_active,
            ],
        ], Response::HTTP_OK);
    }
}
