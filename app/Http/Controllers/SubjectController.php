<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;

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

    public function addStudent(SubjectStudent $subjectStudent)
    {
        $subjectStudent->save();

        return new SubjectResource($subjectStudent->subject);
    }
}
