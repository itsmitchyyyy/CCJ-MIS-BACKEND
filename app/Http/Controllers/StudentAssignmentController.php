<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreStudentAssignmentRequest;
use App\Http\Requests\UpdateStudentAssignmentRequest;
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

    public function index(Request $request)
    {
        $studentAssignments = StudentAssignment::when($request->user_id, fn ($query) => $query->where('user_id', $request->user_id))
            ->when($request->assignment_id, fn ($query) => $query->where('assignment_id', $request->assignment_id))
            ->when($request->load_relations, fn ($query) => $query->with(['student', 'assignment']))
            ->get();

        StudentAssignmentResource::withoutWrapping();
        return StudentAssignmentResource::collection($studentAssignments);
    }

   public function update(UpdateStudentAssignmentRequest $request, StudentAssignment $studentAssignment)
   {
        $data = $request->validated();

        $studentAssignment->update($data);

        StudentAssignmentResource::withoutWrapping();
        return new StudentAssignmentResource($studentAssignment->load(['student', 'assignment']));
   }
}
