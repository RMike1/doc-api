<?php

use App\Models\Student;
use App\Exports\StudentExport;
use App\Imports\StudentImport;
use App\Services\ExcelService;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Jobs\QueueImport;
use App\Services\Students\StudentService;
use App\Services\Students\Excel\StudentExcel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Student\StudentController;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake();
    Excel::fake();
    $this->excelService = app(ExcelService::class);
    $this->student_service = app(StudentService::class);
});

describe('student-export', function () {

    it('queues student export successfully', function () {
        Student::factory()->count(10)->create();

        $this->excelService->export();

        $file = 'students.xlsx';
        
        Excel::assertQueued($file);

        Excel::assertQueued($file, function (StudentExcel $export) {
            return true;
        });
    });

    it('validates imported Excel file', fn() =>
    $this->postJson(route('students.import'), [
        'file' => null,
    ])->assertStatus(422)
        ->assertJsonValidationErrors(['file']));
});