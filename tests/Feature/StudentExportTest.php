<?php

use App\Models\Student;
use App\Services\Export\ExportStrategyFactory;
use App\Services\Import\ImportService;
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
        $exportStrategyFactory = \Mockery::mock(ExportStrategyFactory::class);
        $exportStrategyFactory->shouldReceive('create')
            ->with('pdf')
            ->andReturnSelf();
        new StudentService(new ImportService, $exportStrategyFactory);
        $this->getJson(route('students.export', ['file_type' => 'excel']))
            ->assertStatus(200)
            ->assertExactJson(['message' => 'Export started!']);
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
});

describe('export students data as pdf', function () {
    it('exports students data as pdf file', function () {
        PDF::fake();
        Student::factory()->count(10)->create();
        $exportStrategyFactory = \Mockery::mock(ExportStrategyFactory::class);
        $exportStrategyFactory->shouldReceive('create')
            ->with('pdf')
            ->andReturnSelf();
        new StudentService(new ImportService, $exportStrategyFactory);
        $response = $this->getJson(route('students.export', ['file_type' => 'pdf']));
        $response->assertStatus(200);
        PDF::assertViewIs('export-pdf')
            ->assertSeeText('Students Data')
            ->assertDontSeeText('No data available');
        $students = Student::get(['first_name', 'last_name', 'age', 'student_no', 'level']);
        PDF::assertViewHas(['students' => $students]);
        $firstStudent = $students->first();
        $fields = ['first_name', 'last_name', 'age', 'level', 'student_no'];
        foreach ($fields as $field) {
            PDF::assertSeeText($firstStudent->$field);
        }
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
