<?php

use App\Http\Controllers\SchoolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(SchoolController::class)->group(function () {
    Route::get('/export', 'export')->name('export');
    Route::post('/import', 'import')->name('import');
    Route::get('/exports', 'exports')->name('exports');
    Route::get('download/{record}', 'download')->name('download');
});
