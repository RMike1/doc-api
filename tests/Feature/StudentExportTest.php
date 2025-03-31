<?php

use App\Models\Student;
use App\Services\Students\Excel\StudentExport;
use App\Services\Students\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
    Excel::fake();
});

describe('export students data with excel', function () {

    it('exports student data as excel file', function () {
        $this->partialMock(StudentService::class, function ($mock) {
            $mock->shouldReceive()->export()->with('excel')->once()->passthru();
        });
        $response = $this->getJson(route('students.export', ['file_type' => 'excel']))
            ->assertStatus(200)
            ->assertExactJson(['message' => 'Excel export started!']);
        Excel::matchByRegex();
        Excel::assertQueued('/exports\/students_\d{14}\.xlsx/', function (StudentExport $export) {
            expect($export->headings())->toBe([
                'First Name',
                'Last Name',
                'Age',
                'Student Number',
                'Level',
            ]);

            return true;
        });
    });

    it('handle errors to export student data as excel', function () {
        $this->mock(StudentService::class)
            ->shouldReceive()
            ->export()
            ->with('excel')
            ->once()
            ->andReturn(['error' => 'Excel export failed. please try again...']);

        $response = $this->getJson(route('students.export', ['file_type' => 'excel']));
        $response->assertStatus(500)
            ->assertExactJson(['error' => 'Excel export failed. please try again...']);
    });
});

describe('export students data as pdf', function () {
    it('exports students data as pdf file', function () {
        PDF::fake();
        Student::factory()->count(10)->create();
        $students = Student::get(['first_name', 'last_name', 'age', 'student_no', 'level']);
        $this->partialMock(StudentService::class, function ($mock) {
            $mock->shouldReceive()->export()->with('pdf')->once()->passthru();
        });
        $response = $this->getJson(route('students.export', ['file_type' => 'pdf']))
            ->assertStatus(200);
        PDF::assertViewIs('export-pdf')
            ->assertSeeText('Students Data')
            ->assertDontSeeText('No data available')
            ->assertViewHas(['students' => $students]);
        $firstStudent = $students->first();
        $fields = ['first_name', 'last_name', 'age', 'level', 'student_no'];
        foreach ($fields as $field) {
            PDF::assertSeeText($firstStudent->$field);
        }
    });

    it('handle errors to export student data as pdf', function () {
        $this->mock(StudentService::class)
            ->shouldReceive()
            ->export()
            ->with('pdf')
            ->once()
            ->andReturn(['error' => 'Pdf export failed. please try again...']);

        $this->getJson(route('students.export', ['file_type' => 'pdf']))
            ->assertStatus(500)
            ->assertExactJson(['error' => 'Pdf export failed. please try again...']);
    });
});

it('returns error when unsupported format is selected', function () {
    $this->mock(StudentService::class)
        ->shouldNotReceive()
        ->export();

    $this->getJson(route('students.export', ['file_type' => 'docx']))
        ->assertStatus(422)
        ->assertInvalid(['file_type']);
});
