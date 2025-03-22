<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\StudentController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('students')->group(function () {
    Route::get('/export', [StudentController::class,'export'])->name('students.export');
    Route::post('/import', [StudentController::class,'import'])->name('students.import');
});