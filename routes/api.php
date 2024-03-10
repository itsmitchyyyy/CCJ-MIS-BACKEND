<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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
  });
});
