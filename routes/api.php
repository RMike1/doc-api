<?php

use App\Http\Controllers\Student\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('students')->group(function () {
    Route::get('/export', [StudentController::class, 'export'])->name('students.export');
    Route::get('/download', [StudentController::class, 'download'])->name('students.download');
    Route::post('/import', [StudentController::class, 'import'])->name('students.import');
});
