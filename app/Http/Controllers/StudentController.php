<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Enums\AccessType;
use App\Enums\Status;
use App\Http\Resources\SubjectStudentResource;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = User::search($request->search ?? '')
        ->query(function ($query) {
            return $query->where([
                ['access_type', '=', AccessType::STUDENT],
                ['status', '=', $request->status ?? Status::ACTIVE]
            ])
            ->orderBy('created_at', 'desc');
        })
        ->get();

        return response()->json($students, 200);
    }

    public function show(User $student)
    {
        return response()->json($student, 200);
    }

    public function getSubjects(User $student)
    {
        SubjectStudentResource::withoutWrapping();
        return SubjectStudentResource::collection($student->subjects);
    }
}
