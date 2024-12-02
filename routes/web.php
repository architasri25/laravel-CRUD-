<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/add-student',function(){
    return view('form');
});

Route::post('add-student',[StudentController::class,'addStudent'])->name('addStudent');
Route::get('/get-students',function(){
    return view('students');
});

Route::get('/get-all-students',[StudentController::class,'getStudents'])->name('getStudents');

Route::get('/editUser/{id}', [StudentController::class, 'getStudentData']);

Route::post('update-data', [StudentController::class, 'updateStudent'])->name('updateStudent');

// In your routes/web.php file
Route::delete('/delete-data/{id}', [StudentController::class, 'deleteData'])->name('deleteData');





