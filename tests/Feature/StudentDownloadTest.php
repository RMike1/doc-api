<?php

use App\Models\Student;
use App\Services\Students\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Students\Excel\StudentExport;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
    Excel::fake();
    PDF::fake();
});


it('downloads student data as excel file', function () {
    Student::factory()->count(10)->create();
    $this->getJson(route('students.download', ['file_type' => 'excel']))
    ->assertStatus(200);
    Excel::matchByRegex();
    Excel::assertDownloaded('/students_\d{14}\.xlsx/', function (StudentExport $export) {
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


it('downloads student data as pdf', function () {
    Student::factory()->count(10)->create();
    $students = Student::get(['first_name', 'last_name', 'age', 'student_no', 'level']);
    $response = $this->getJson(route('students.download', ['file_type' => 'pdf']));
    $response->assertStatus(200);

    PDF::assertViewIs('export-pdf');
    PDF::assertViewHas('students', $students);
});

it('returns error for unsupported format', function () {
    $response = $this->getJson(route('students.download', ['file_type' => 'docx']));
    $response->assertStatus(422);
});


it('returns error when no data to export', function () {
    $this->mock(StudentService::class)
        ->shouldReceive()->download()->with('pdf')
        ->andThrow(new \Exception('No data to export!'));

    $response = $this->getJson(route('students.download', ['file_type' => 'pdf']));
    $response->assertStatus(500)
        ->assertJson(['error' => 'No data to export!']);
});