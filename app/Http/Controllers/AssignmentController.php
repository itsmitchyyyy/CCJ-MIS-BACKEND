<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function store(StoreAssignmentRequest $request) {
        $data = $request->validated();

        $data['due_date'] = date('Y-m-d', strtotime($data['due_date']));
        $data['due_time'] = date('H:i:s', strtotime($data['due_time']));

        $assignment = Assignment::create($data);

        return response()->json(['message' => 'Assignment created successfully', 'data' => $assignment]);
    }

    public function index(Request $request) {
        $assignments = Assignment::when($request->subject_id, function ($query) use ($request) {
            return $query->where('subject_id', $request->subject_id);
        })
        ->when($request->student_id, function ($query) use ($request) {
           return $query->with(['studentAssignments' => function ($query) use ($request) {
               $query->where('user_id', $request->student_id);
           }]);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        AssignmentResource::withoutWrapping();
        return AssignmentResource::collection($assignments);
    }
}
