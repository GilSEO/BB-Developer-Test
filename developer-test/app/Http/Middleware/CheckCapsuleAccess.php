<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MessageCapsule;

class CheckCapsuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $capsule = $request->route('capsule');

        if (!$capsule) {
            return response()->json(['Error' => 'Capsule not found'], 404);
        }

        if ($capsule->user_id !== auth()->id()) {
            return response()->json(['Error' => 'Unauthorized access'], 403);
        }

        if (now()->lt($capsule->scheduledOpeningTime)) {
            return response()->json(["Error" => 'It is not time to open the capsule yet'], 403);
        }

        return $next($request);
    }
}
