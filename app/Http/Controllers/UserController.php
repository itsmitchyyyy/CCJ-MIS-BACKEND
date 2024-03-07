<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function user(Request $request) {
        return response()->json(new UserResource($request->user()));
    }

    public function update(UpdateUserRequest $request, User $user) {
        $data = $request->validated();

        if ($request->hasFile('profile_picture')) {
           $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures');

        }

        $user->update($data);

        return new UserResource($user);
    }

    public function store(StoreUserRequest $request) {
        $data = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures');
        }

        $user = User::create($data);

        return new UserResource($user);
    }
}
