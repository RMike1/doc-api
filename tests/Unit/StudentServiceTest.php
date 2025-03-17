<?php

use App\Models\Student;
use App\Enums\FileExtension;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\Students\StudentService;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Students\Excel\StudentExport;
use App\Services\Students\Excel\StudentImport;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
    Excel::fake();
    $this->service = new StudentService();
});

describe('pdf tests..', function(){

        it('exports students as pdf', function () {
            PDF::fake();
            Student::factory()->count(10)->create();
            $students = Student::get(['first_name', 'last_name', 'age', 'student_no', 'level']);
            $response = $this->service->export('pdf');
            PDF::assertFileNameIs($response['file_path'])
                ->assertViewIs('export-pdf')
                ->assertSeeText('Students Data')
                ->assertDontSeeText('Unauthorized')
                ->assertFileNameIs($response['file_path'])
                ->assertViewHas(['students' => $students]);
            $firstStudent = $students->first();
            $fields = ['first_name', 'last_name', 'age', 'level', 'student_no'];
            foreach ($fields as $field) {
                PDF::assertSeeText($firstStudent->$field);
            }
            expect($response)->toBe(['message' => 'PDF export started!', 'file_path' => $response['file_path']]);
        });

        it('returns error when no data to export',fn() =>
            expect($this->service->export('pdf'))->toBe(['error' => 'No data to export!'])
        );
});

it('exports students as excel with dynamic filename ', function () {
    $this->service->export('excel');
    Excel::matchByRegex();
    Excel::assertQueued('/exports\/students_\d{14}\.xlsx/', function (StudentExport $export) {
         $query = $export->query();
         $columns = $query->getQuery()->columns;
         expect($columns)->toBe(['first_name', 'last_name', 'age', 'student_no', 'level']);
         expect($export->headings())->toBe([
             'First Name',
             'Last Name',
             'Age',
             'Student Number',
             'Level'
         ]);
        return true;
    });
});

it('returns an error for unsupported file types on export', fn() =>
    expect($this->service->export('txt'))->toBe(['error' => 'Unsupported file type!'])
);

it('imports students from excel', function () {
    $file = UploadedFile::fake()->create('students.xlsx');
    Excel::shouldReceive('queueImport')->once()->with(Mockery::type(StudentImport::class), $file, null, 'Xlsx');
    expect($this->service->import($file))->toBe(['message' => 'Student data import is in progress with xlsx extension...']);
});

it('imports students from Csv', function () {
    $file = UploadedFile::fake()->create('students.csv');
    Excel::shouldReceive('queueImport')->once()->with(Mockery::type(StudentImport::class), $file, null, 'Csv');
    expect($this->service->import($file))->toBe(['message' => 'Student data import is in progress with csv extension...']);
});

it('rejects an invalid file types on import', function () {
    $file = UploadedFile::fake()->create('students.txt', 100, 'txt');
    expect($this->service->import($file))->toBe(['error' => 'Invalid file format. Only xlsx, csv, or xls are allowed.']);
});
