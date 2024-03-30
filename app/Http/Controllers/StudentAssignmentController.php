<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreStudentAssignmentRequest;
use App\Models\StudentAssignment;
use App\Models\User;
use App\Models\Assignment;
use App\Http\Resources\StudentAssignmentResource;

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

    public function checkStudentAssignment(User $student, Assignment $assignment) 
    {
        $studentAssignment = StudentAssignment::where('user_id', $student->id)
            ->where('assignment_id', $assignment->id)
            ->first();

        if (!$studentAssignment) {
            return response()->json(['message' => 'Student assignment not found'], 404);
        }

        StudentAssignmentResource::withoutWrapping();
        return new StudentAssignmentResource($studentAssignment);
    }
}
