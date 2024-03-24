<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendance = Attendance::when($request->user_id, function ($query) use ($request) {
            return $query->where('user_id', $request->user_id);
        })->when($request->subject_id, function ($query) use ($request) {
            return $query->where('subject_id', $request->subject_id);
        })->when($request->date, function ($query) use ($request) {
            return $query->where('date', $request->date);
        })->when($request->status, function ($query) use ($request) {
            return $query->where('status', $request->status);
        })
        ->orderBy('date', 'DESC')->get();

        
        AttendanceResource::withoutWrapping();
        return AttendanceResource::collection($attendance);
    }

    public function store(StoreAttendanceRequest $request)
    {
        $data = $request->validated();

        $attendanceQuery = Attendance::where('user_id', $data['user_id'])
            ->where('subject_id', $data['subject_id'])
            ->where('date', $data['date']);

        if ($attendanceQuery->exists()) {
            $attendanceQuery->update(['status' => $data['status']]);
            return new AttendanceResource($attendanceQuery->first());
        }
        
        $attendance = Attendance::create($data);
        return new AttendanceResource($attendance);
    }
}
