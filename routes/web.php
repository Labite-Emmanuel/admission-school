<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\Admission;
use App\Http\Middleware\CheckAuthSession;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    // Artisan::call('optimize');
    Artisan::call('clear-compiled');
    return '<h1>Cache Clear ok!</h1>';
});

// API admissions (accessible sans session)
Route::get('api/admissions', [Admission::class, 'getAllAdmissionsApi']);
Route::get('api/academic-years/{code}/{status}', [Admission::class, 'updateAcademicYearApi']);
Route::get('api/academic-years-parent/{code}/{status}', [Admission::class, 'updateAcademicYearApiByParent']);

Route::get('/', [AuthController::class, 'showLoginForm'])->name('home');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('signin', [AuthController::class, 'login'])->name('signin');
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('forgot-password', [AuthController::class, 'updatePassword'])->name('forgot-password.update');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register.create', [AuthController::class, 'register'])->name('register.create');

// Routes protégées - authentification requise (via session)
Route::middleware([CheckAuthSession::class])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    /////////////////////////////////////////////////////// Admission //////////////////////////////////////////////
    Route::get('admission', [Admission::class, 'get_all_Admission'])->name('admission');
    Route::get('home-admission', [Admission::class, 'get_all_HomeAdmission'])->name('home-admission');
    Route::post('home-admission', [Admission::class, 'get_all_HomeAdmission'])->name('home-admission');
    Route::get('admission-redirect', [Admission::class, 'get_Redirection']);
    Route::get('duplicate-admission', [Admission::class, 'duplicateAdmission']);
    Route::delete('delete-admission/{code_student}', [Admission::class, 'deleteAdmission'])->name('delete-admission');
    Route::post('primaty-step', [Admission::class, 'insert_dashAdmin'])->name('admission');
    Route::post('post_info_step1', [Admission::class, 'PostInfo_Step1']);
    Route::post('post_info_step2', [Admission::class, 'PostInfo_Step2']);
    Route::post('post_info_step3', [Admission::class, 'PostInfo_Step3']);
    Route::post('post_info_step4', [Admission::class, 'PostInfo_Step4']);
    Route::post('post_info_step5', [Admission::class, 'PostInfo_Step5']);
    Route::post('post_info_step6', [Admission::class, 'PostInfo_Step6']);
    Route::post('update_student_parent', [Admission::class, 'Update_Student_Parent']);
    Route::get('/search-parents', [Admission::class, 'search'])->name('admission');
    
    Route::get('/get_step1_data', [Admission::class, 'getStep1Data']);
    Route::get('/get_step2_data', [Admission::class, 'getStep2Data']);
    Route::get('/get_step3_data', [Admission::class, 'getStep3Data']);
    Route::get('/get_step4_data', [Admission::class, 'getStep4Data']);
    Route::get('/get_step5_data', [Admission::class, 'getStep5Data']);
    Route::get('/get_step6_data', [Admission::class, 'getStep6Data']);
    
    Route::get('/in-process/{id_user}', [Admission::class, 'getstudeInprocess']);
    Route::get('/registration-validate/{id_user}', [Admission::class, 'getstudeRegistration']);
    Route::get('/items-ordering/{code}', [Admission::class, 'getstudeItemsOrdering']);
    Route::get('/admission-validate', [Admission::class, 'getstudeAdmission']);
    Route::get('/ordering-validate/{class}/{code}', [Admission::class, 'getstudeAdmission2']);
    Route::get('/validate-ordering', [Admission::class, 'getstudeAdmission3']);
    Route::get('/waiting-for-instalment/{id_user}', [Admission::class, 'waitingForInstalment'])->name('waiting-for-instalment');
    Route::get('/view-waiting-instalment/{code}', [Admission::class, 'waitingInstalment']);
    Route::get('/instalment/{id}', [Admission::class, 'Instalmentstep'])->name('instalment');
    Route::post('/instalment1', [Admission::class, 'instalment1'])->name('instalment');
});



















