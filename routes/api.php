<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
   // User Routes
   Route::controller(UserController::class)->group(function () {
       Route::get('/user', 'user');
       Route::put('/user/{user}', 'update');
       Route::post('/user', 'store');
       Route::get('/users', 'list');
       Route::delete('/user/{user}', 'destroy');
       Route::get('/user/{user}', 'show');
   });

   // Auth Routes
   Route::controller(AuthController::class)->group(function () {
      Route::post('/logout', 'logout');
      Route::post('/change-password', 'changePassword');
  });

    Route::apiResource('/subjects', SubjectController::class)->only(['store', 'index']);
    Route::apiResource('/teachers', TeacherController::class)->only(['index', 'destroy', 'show']);
    Route::apiResource('/teacher-attendances', TeacherAttendanceController::class)->only(['store', 'index']);
    Route::apiResource('/students', StudentController::class)->only(['index', 'show']);
    Route::apiResource('/attendances', AttendanceController::class)->only(['store', 'index']);
    Route::apiResource('/documents', DocumentController::class)->only(['store', 'index', 'update', 'destroy']);
    Route::apiResource('/assignments', AssignmentController::class)->only(['store', 'index']);
    Route::apiResource('/student-assignments', StudentAssignmentController::class)->only(['store', 'index', 'update']);
    Route::apiResource('/facilities', FacilityController::class)->only(['store', 'index', 'destroy', 'update']);
    Route::apiResource('/announcements', AnnouncementController::class)->only(['store', 'index', 'destroy']);
    Route::apiResource('/settings', SettingsController::class)->only(['store', 'index']);

    Route::controller(SubjectController::class)->group(function () {
        Route::post('/subjects/{subject}/students', 'addStudent');
        Route::get('/subjects/{subject}/students', 'fetchSubjectStudents');
        Route::delete('/subjects/{subject}/students/{student}', 'removeStudent');
        Route::post('/subjects/{subject}/students/{student}/grades', 'updateGrade');
    });

    Route::controller(StudentController::class)->group(function () {
        Route::get('/students/{student}/subjects', 'getSubjects');
    });

    Route::controller(StudentAssignmentController::class)->group(function () {
        Route::get('/student-assignments/{student}/{assignment}/exists', 'checkStudentAssignment');
    });

    Route::controller(DocumentController::class)->group(function () {
        Route::post('/document-requests', 'addRequest');
        Route::get('/document-requests', 'fetchDocumentRequests');
        Route::put('/document-requests/{documentRequest}', 'updateDocumentRequest');
    });

    Route::controller(FacilityController::class)->group(function () {
        Route::post('/facility-requests/{facility}', 'createFacilityRequest');
        Route::get('/facility-requests', 'fetchFacilityRequests');
        Route::put('/facility-requests/{requestFacility}', 'updateFacilityRequest');
    });
});
