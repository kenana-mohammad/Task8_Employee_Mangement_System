<?php

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Auth
  Route::controller(AuthController::class)->group(function(){
    //Register

         Route::post('register','Register');
         //login
         Route::post('login','login');
         //logout
         Route::post('logout','logout')->middleware(['auth:api']);

  });

  Route::group(['middleware'=>'auth:api'],function(){
//Department
 Route::apiResource('department',DepartmentController::class);
//استرجاع المحذوف
Route::delete('restoreDepartment/{id}',[DepartmentController::class,'restore']);

//حذف النهائي
Route::delete('forceDeleteDepartment/{id}',[DepartmentController::class,'force']);
//----------------------------
//Employee
 Route::apiResource('employee',EmployeeController::class);
//Restore Delete Employee
Route::delete('restoreEmployee/{id}',[EmployeeController::class,'Restore']);
//ForceDelete
Route::delete('ForceEmployee/{id}',[EmployeeController::class,'ForceDelete']);
//Project
Route::apiResource('project',ProjectController::class);
//notes
Route::apiResource('note',NoteController::class);
//getNotesByDepartments
Route::get('getDepartmentNotes/{id}',[EmployeeController::class,'getDepartmentNotes']);
});


