<?php

namespace App\Http\Controllers;


use App\Models\MessageCapsule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageCapsuleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'scheduledOpeningTime' => 'required|date|after:now',
        ]);

        $capsule = MessageCapsule::create([
            'user_id' => Auth::id(),
            'message' => encrypt($validated['message']),
            'scheduledOpeningTime' => $validated['scheduledOpeningTime'],
        ]);

        return response()->json(['message' => 'Capsule has been succesfully created!', 'data' => $capsule], 201);
    }

    public function index()
    {
        $capsules = MessageCapsule::where('opened', false)
            ->where('scheduledOpeningTime', '<=', now())
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($capsules);
    }
}
