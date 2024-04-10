<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Enums\AccessType;
use App\Enums\Status;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = User::where([
            ['access_type', '=', AccessType::TEACHER],
            ['status', '=', $request->status ?? Status::ACTIVE]
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json($teachers, 200);
    }
}
