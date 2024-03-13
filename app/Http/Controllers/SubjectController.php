<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function store(StoreSubjectRequest $request)
    {
        $data = $request->validated();

        $subject = Subject::create($data);

        return new SubjectResource($subject);
    }
}
