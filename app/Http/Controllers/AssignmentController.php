<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAssignmentRequest;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function store(StoreAssignmentRequest $request) {
        $data = $request->validated();

        $data['due_date'] = date('Y-m-d', strtotime($data['due_date']));

        $assignment = Assignment::create($data);

        return response()->json(['message' => 'Assignment created successfully', 'data' => $assignment]);
    }
}
