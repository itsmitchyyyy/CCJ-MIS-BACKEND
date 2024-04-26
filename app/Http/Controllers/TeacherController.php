<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Enums\AccessType;
use App\Enums\Status;
use App\Http\Resources\UserResource;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = User::search($request->search ?? '')
        ->query(function($query) {
           return $query->where([
                ['access_type', '=', AccessType::TEACHER],
                ['status', '=', $request->status ?? Status::ACTIVE]
            ])
            ->orderBy('created_at', 'desc');
        })
        ->get();

        return response()->json($teachers, 200);
    }

    public function destroy(User $teacher) {
        $teacher->delete();
        return response()->noContent();
    }

    public function show(User $teacher) {
        UserResource::withoutWrapping();
        return new UserResource($teacher);
    }
}
