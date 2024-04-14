<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTeacherAttendanceRequest;

class TeacherAttendanceController extends Controller
{
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
