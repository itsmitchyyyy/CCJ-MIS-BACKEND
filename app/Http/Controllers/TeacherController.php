<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Enums\AccessType;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('access_type', AccessType::TEACHER)->get();

        return response()->json($teachers, 200);
    }
}
