<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $data = $request->validated();

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'contact_number' => $data['contact_number'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password'])
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => new UserResource($user), 'token' => $token]);
    }

    public function login(LoginRequest $request) {
        $data = $request->validated();

        $user = User::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Username or password is incorrect!'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => new UserResource($user), 'token' => $token]);
    }

    public function logout() {
        try {
            auth()->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th, 'message' => 'Something went wrong!']);
        }
    }

    public function changePassword(ChangePasswordRequest $request) {
        $data = $request->validated();

        $user = auth()->user();

        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json(['message' => 'Current Password does not match', 'errors' => ['current_password' => ['Current Password does not match']]], 422);
        }

        $user->update(['password' => Hash::make($data['new_password'])]);
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Password changed successfully!']);
        
    }
}
