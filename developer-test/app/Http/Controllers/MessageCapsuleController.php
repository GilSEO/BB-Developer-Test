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
            'opened' => false,
        ]);

        return response()->json(['message' => 'Capsule has been succesfully created!', 'data' => $capsule], 201);
    }

    public function index()
    {
        $capsules = MessageCapsule::where('user_id', Auth::id())
            ->get();
        return response()->json($capsules);
    }

    public function show(MessageCapsule $capsule)
    {

        if (!$capsule->opened) {
            $capsule->opened = true;
            $capsule->save();
        }
        try {
            $capsule->message = decrypt($capsule->message);
        } catch (\Exception $e) {
            return response()->json(['Error' => 'Failed to decrypt message'], 500);
        }
        return response()->json($capsule);

    }

    public function update(Request $request, MessageCapsule $capsule)
    {
        $validated = $request->validate([
            'message' => 'sometimes|required|string',
            'scheduledOpeningTime' => 'sometimes|required|date|after:now',
            'opened' => 'sometimes|required|boolean',
        ]);

        if (array_key_exists('message', $validated)) {
            $validated['message'] = encrypt($validated['message']);
        }

        $capsule->update($validated);

        $capsule->message = decrypt($capsule->message);

        return response()->json($capsule);
    }
}
