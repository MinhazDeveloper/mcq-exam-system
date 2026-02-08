<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role' => 'student'
        ]);

        $user->tokens()->delete();
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Invalid Credentials'], 401);
        }
        //for preventing the user who already loged in google.
        if ($user->provider === 'google') {
            return response([
                'message' => 'Please login using Google'
            ], 403);
        }
        // last login time update and old token delete
        $user->update([
            'last_login_at' => now()
        ]);
        $user->tokens()->delete();
        
        $token = $user->createToken('myapptoken')->plainTextToken;
        return response([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token
        ], 200);
    }

    //Google Redirect
    public function redirectToGoogle() {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback() {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            $user = User::updateOrCreate([
                'email' => $googleUser->getEmail(),
            ], [
                'name' => $googleUser->getName(),
                'provider_id' => $googleUser->getId(),
                'provider' => 'google',
                // 'password' => null,
                'password' => Hash::make(Str::random(24))
            ]);

            $user->tokens()->delete();
            $token = $user->createToken('myapptoken')->plainTextToken;

            // Vue Frontend redirect
            // return redirect(env('FRONTEND_URL') . '/auth/callback?token=' . $token);
            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
            
        } catch (\Exception $e) {
            return response(['error' => 'Google authentication failed'], 401);
        }
    }

    public function logout(Request $request) {
        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json(['message' => 'Logged out']);
    }
}
