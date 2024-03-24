<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Enums\AccessType;
use App\Enums\Status;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = User::where([
            ['access_type', '=', AccessType::STUDENT],
            ['status', '=', $request->status ?? Status::ACTIVE]
        ])->get();

        return response()->json($students, 200);
    }

    public function show(User $student)
    {
        return response()->json($student, 200);
    }
}
