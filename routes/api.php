<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admission;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Liste de toutes les admissions (données complètes)
Route::get('/admissions', [Admission::class, 'getAllAdmissionsApi']);

// Modification du status d'un enregistrement academic_year par son code
Route::get('/academic-years/{code}/{status}', [Admission::class, 'updateAcademicYearApi']);
