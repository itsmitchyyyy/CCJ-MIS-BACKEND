<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\StoreSubjectStudentRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use App\Models\SubjectStudent;
use Carbon\Carbon;

class SubjectController extends Controller
{

    public function index()
    {
        $subjects = Subject::all();

        SubjectResource::withoutWrapping();
        return SubjectResource::collection($subjects);
    }

    public function store(StoreSubjectRequest $request)
    {
        $data = $request->validated();

        $subject = Subject::create($data);

        return new SubjectResource($subject);
    }

    public function addStudent(Subject $subject, StoreSubjectStudentRequest $request)
    {
        $data = $request->validated();

        $subjectStudentData = [];

        foreach($data['user_id'] as $userId) {
            $subjectStudentData[] = [
                'subject_id' => $subject->id,
                'user_id' => $userId,
                'created_at' => Carbon::now(),
            ];
        }

        $subjectStudent = SubjectStudent::insert($subjectStudentData);

        return response()->json($subjectStudentData, 201);
    }
}
