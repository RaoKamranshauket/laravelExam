<?php

use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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
//Route::get('public/updates', 'FileController@view')->name('file.show');
Route::view('pdf','iframes.pdf')->name('file.show');
Route::post('/exam/save',[ExamController::class,'saveExam'])->name('exam.result.save');
Route::post('/exam/submit',[ExamController::class,'submitExam'])->name('exam.submit');
Route::post('/exam-section/submit',[ExamController::class,'submitExamSection'])->name('exam.section.submit');
Route::get('/exam/result',[ExamController::class,'examResult'])->name('exam.result');
Route::post('/exam-section/continue',[ExamController::class,'examSectionStart'])->name('exam.continue.section');
Route::post('exam/complete_steps', [ExamController::class,'complete_steps'])->name('exam.complete_steps');

Route::get('/attend/exam/{exam_id}/{user_id}',[ExamController::class,'attendExam'])->name('exam.attend');
Route::namespace('App\Http\Controllers')->group(function(){

    Route::controller('Auth\LoginController')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/login', 'login')->name('login');
    });

   // All Authenticated Routes for Admin
    Route::middleware('auth')->as('admin.')->group(function(){
         // Dashboard Routes
        Route::controller('Dashboard\DashboardController')->group(function(){
            Route::get('/dashboard', 'index')->name('dashboard');
            Route::post('/logout', 'logout')->name('logout');
        });
        // users routes
        Route::controller('User\UserController')->as('users.')->group(function(){
            Route::get('/users', 'index')->name('index');
            Route::get('/create/user', 'create')->name('create');
            Route::post('/create/user', 'store')->name('store');
            Route::get('/edit/user/{id}', 'edit')->name('edit');
            Route::post('/update/user', 'update')->name('update');
            Route::post('/destroy/user', 'destroy')->name('destroy');
            Route::post('/email-exist', 'checkEmail')->name('email.exist');
        });
        // exams
        Route::controller('Exam\ExamController')->as('exams.')->group(function(){
            Route::get('/exams', 'index')->name('index');
            Route::get('/create/exam', 'create')->name('create');
            Route::get('/edit/exam/{id}','edit')->name('edit');
            Route::post('/create/exam', 'store')->name('store');
            Route::post('/update/exam', 'update')->name('update');
            Route::post('/destroy/exam', 'delete')->name('delete');

        });
        // Edit Exam Question Types

            Route::controller('Exam\ExamQuestionController')->as('exam_question.')->group(function(){
            Route::get('/exams-questions/edit/{exam_id}/{section_id}', 'edit')->name('edit');
            Route::post('/exam-questions/update', 'update')->name('update');
            Route::post('/exam-questions/delete', 'delete')->name('delete');
        });
        // assign student to exams
        Route::controller('Exam\ExamRegisterController')->as('exams.')->group(function(){
            Route::get('/register/exam', 'index')->name('register_index');
            Route::get('/register-exam', 'registerExam')->name('register_exam');
            Route::post('/assign-student/exam/', 'assignStudent')->name('assign_student.store');
            Route::post('/load-students', 'loadStudents')->name('load_students');
        });
        // exam result
        // Route::controller('Exam\ExamResultController')->as('exam_results.')->group(function(){
        //   //  Route::get('view-result/{id}', 'view')->name('view');
        // });
        // Reporting
        Route::controller('Exam\ReportController')->as('reporting.')->group(function(){
            Route::get('/reporting', 'index')->name('index');
            Route::post('/get-students', 'getStudents')->name('get_students');
        });
    });

});

