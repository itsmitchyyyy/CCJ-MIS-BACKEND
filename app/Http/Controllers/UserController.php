<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function user(Request $request) {
        return response()->json(new UserResource($request->user()));
    }
}
