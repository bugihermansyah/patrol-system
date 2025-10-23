<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        // Jika master shift global:
        $shifts = Shift::query()->orderBy('start_time')->get();

        // Jika shift per user (uncomment jika pakai kolom user_id di shifts):
        // $shifts = Shift::where('user_id', $request->user()->id)
        //                ->orderBy('start_time')
        //                ->get();

        return response()->json($shifts, 200);
    }
}
