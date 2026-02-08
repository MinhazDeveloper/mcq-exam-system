<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{   
    public function userAll(Request $request)
    {   
        $user_all = User::select([
            'id', 
            'name', 
            'email', 
            'role', 
            'email_verified_at', 
            'last_login_at', 
            'created_at'
        ])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Hello admin',
            'data' => $user_all,
        ]);
    }
    //store user
    public function storeUser(Request $request)
    {
        // ১. ডাটা ভ্যালিডেশন
        $fields = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|string',
        ]);

        // ২. ইউজার তৈরি
        $user = User::create([
            'name'     => $fields['name'],
            'email'    => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role'     => $fields['role'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data'    => $user
        ], 201);
    }
    //update user
    public function updateUser(Request $request, $id)
    {
        
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|string',
            // 'role' => 'nullable|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|min:6',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data'    => $user
        ], 200);
    }
    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'message' => 'Welcome admin',
        ]);
    }
}
