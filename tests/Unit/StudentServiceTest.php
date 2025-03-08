<?php
use App\Models\Student;
use App\Enums\FileExtension;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Services\Students\StudentService;
use App\Services\Students\Excel\StudentExport;
use App\Services\Students\Excel\StudentImport;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Storage::fake();
    Excel::fake();
    $this->service = new StudentService();
});

it('exports students as pdf', function () {
    PDF::fake();
    Student::factory()->count(10)->create();
    $response = $this->service->export('pdf');
    PDF::assertFileNameIs($response);
});

it('exports students as excel', function () {
    $this->service->export('excel');
    Excel::matchByRegex();
    Excel::assertStored('/exports\/employees_\d{14}\.xlsx/', function (StudentExport $export) {
        return true;
    });
});

it('returns an error for unsupported file types on export',fn() =>
    expect($this->service->export('txt'))->toBe(['unsupported file!'])
);

it('imports students from excel', function () {
    $file = UploadedFile::fake()->create('students.xlsx');
    Excel::shouldReceive('queueImport')->once()->with(Mockery::type(StudentImport::class), $file, null, 'Xlsx');
    expect($this->service->import($file))->toBe(['message' => 'Student data import is in progress with xlsx extension...']);
});

it('rejects an invalid file types on import', function () {
    $file = UploadedFile::fake()->create('students.txt', 100, 'txt');
    expect($this->service->import($file))->toBe(['error' => 'Invalid file format. Only xlsx, csv, or xls are allowed.']);
});