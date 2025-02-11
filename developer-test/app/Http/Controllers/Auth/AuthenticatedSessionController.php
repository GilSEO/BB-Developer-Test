<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function apiLogin(Request $request)
    {
        $creds = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($creds)) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        // Delete old tokens (optional)
        $user->tokens()->delete();

        // Generate API token for Vue
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($request->wantsJson()) {
            $currentToken = $user->tokens()->first();

            if (!$currentToken) {
                $token = $user->createToken('API Token')->plainTextToken;
            }

            return response()->json([
                'response' => 'Login Succesful',
                'token' => $token,
                'user' => $user,
            ]);

        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse|RedirectResponse
    {

        if ($request->expectsJson()) {
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
            }
            return response()->json(['message' => 'Logged out successfully']);
        }


        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function apiLogout(Request $request): JsonResponse
    {

        // Log out the user from the "web" guard
        Auth::guard('web')->logout();

        // Invalidate the session and regenerate the CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
