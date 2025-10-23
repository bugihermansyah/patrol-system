<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patrol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatrolController extends Controller
{
    public function index()
    {
        $patrols = Patrol::where('user_id', Auth::id())->latest()->get();
        return response()->json($patrols);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'location_name' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $patrol = Patrol::create($validated);

        return response()->json([
            'message' => 'Patrol record created successfully',
            'data' => $patrol,
        ]);
    }

    public function show(Patrol $patrol)
    {
        if ($patrol->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($patrol);
    }

    public function destroy(Patrol $patrol)
    {
        if ($patrol->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $patrol->delete();
        return response()->json(['message' => 'Patrol deleted successfully']);
    }
}
