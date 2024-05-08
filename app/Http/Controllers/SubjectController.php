<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\StoreSubjectStudentRequest;
use App\Http\Requests\UpdateStudentGradeRequest;
use App\Http\Resources\SubjectResource;
use App\Http\Resources\SubjectStudentResource;
use App\Models\Subject;
use App\Models\SubjectStudent;
use Carbon\Carbon;

class SubjectController extends Controller
{

    public function index(Request $request)
    {
        $subjects = Subject::search($request->search ?? '')
        ->query(function ($query) use ($request) {
            return $query->when($request->teacher_id, function ($query) use ($request) {
                return $query->where('user_id', $request->teacher_id);
            })
            ->orderBy('created_at', 'desc');
        })
        ->get();

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

    public function fetchSubjectStudents(Subject $subject, Request $request)
    {
        $subjectStudents = SubjectStudent::search($request->search ?? '')
        ->query(function ($query) use ($subject) {
            return $query->select('subject_students.*', 'users.first_name', 'users.last_name')
                ->join('users', 'users.id', '=', 'subject_students.user_id')
                ->join('subjects', 'subjects.id', '=', 'subject_students.subject_id')
                ->where('subject_id', $subject->id)
                ->orderBy('created_at', 'desc');
        })
        ->get();

        SubjectStudentResource::withoutWrapping();
        return SubjectStudentResource::collection($subjectStudents);
    }

    public function removeStudent(Subject $subject, $studentId)
    {
        SubjectStudent::where('subject_id', $subject->id)
            ->where('user_id', $studentId)
            ->delete();

        return response()->noContent();
    }

    public function updateGrade(Subject $subject, $studentId, UpdateStudentGradeRequest $request)
    {
        $data = $request->validated();

        $subjectStudent = SubjectStudent::where('subject_id', $subject->id)
            ->where('user_id', $studentId)
            ->first();

        $terms = $data['grade'];
        $newGrade = [];
        foreach($terms as $key => $value) {
            $newGrade[$terms[$key]['term']] = $terms[$key]['value'];
        }

        $subjectStudent->grade = $newGrade;
        $subjectStudent->save();

        return response()->json($subjectStudent, 200);
    }
}
