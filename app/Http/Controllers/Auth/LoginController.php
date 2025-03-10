<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            // Check if this is an API request (either from /api route or with JSON Accept header)
            $isApiRequest = $request->is('api/*') || $request->wantsJson();

            if (!Auth::attempt($credentials)) {
                if ($isApiRequest) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return back()->withErrors(['email' => 'Invalid credentials']);
            }

            $user = $request->user();
            
            if ($isApiRequest) {
                // API token-based authentication
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ]);
            }

            // Web session-based authentication
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            if ($isApiRequest) {
                return response()->json([
                    'error' => 'Server Error',
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Login failed']);
        }
    }

    public function logout(Request $request)
    {
        // Check if this is an API request
        $isApiRequest = $request->is('api/*') || $request->wantsJson();
        
        if ($isApiRequest) {
            // API token revocation
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out'], 200);
        }

        // Web session logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}