<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTeacherAttendanceRequest;
use App\Models\TeacherAttendance;
use App\Http\Resources\TeacherAttendanceResource;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request) {
        $attendance = TeacherAttendance::when($request->user_id, function ($query) use ($request) {
            return $query->where('user_id', $request->user_id);
        })->when($request->date, function ($query) use ($request) {
            return $query->where('date', $request->date);
        })->when($request->status, function ($query) use ($request) {
            return $query->where('status', $request->status);
        })
        ->orderBy('date', 'DESC')->get();

        TeacherAttendanceResource::withoutWrapping();
        return TeacherAttendanceResource::collection($attendance);
    }

    public function store(StoreTeacherAttendanceRequest $request) {
        $data = $request->validated();

        $attendanceQuery = TeacherAttendance::where('user_id', $data['user_id'])
            ->where('date', $data['date']);

        if ($attendanceQuery->exists()) {
            $attendanceQuery->update(['status' => $data['status']]);
            return new TeacherAttendanceResource($attendanceQuery->first());
        }

        $attendance = TeacherAttendance::create($data);
        return new TeacherAttendanceResource($attendance);
    }
}
