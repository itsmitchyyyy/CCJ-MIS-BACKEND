<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreStudentAssignmentRequest;
use App\Models\StudentAssignment;

class StudentAssignmentController extends Controller
{
    public function store(StoreStudentAssignmentRequest $request)
    {
        $data = $request->validated();

        $studentAssignmentFilePath = "assignments/{$data['user_id']}/{$data['assignment_id']}";

        $data['file_paths'] = collect($data['file_paths'])
            ->map(fn ($file) => $file->store($studentAssignmentFilePath))
            ->toJson();

        $studentAssignment = StudentAssignment::create($data);

        return response()->json(['message' => 'Student assignment created successfully', 'data' => $studentAssignment]);
    }
}
