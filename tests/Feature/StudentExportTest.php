<?php

use App\Models\Student;
use Mockery\MockInterface;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use App\Services\Students\StudentService;

use App\Services\Students\Excel\StudentExport;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $response=$this->getJson(route('students.export', ['file_type' => 'excel']))
            ->assertStatus(200)
            ->assertExactJson(['message' => 'Excel export started!']);
        Excel::matchByRegex();
        Excel::assertQueued('/exports\/students_\d{14}\.xlsx/', function (StudentExport $export) {
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
        $response=$this->getJson(route('students.export', ['file_type' => 'pdf']))
            ->assertStatus(200);
            PDF::assertViewIs('export-pdf')
            ->assertSeeText('Students Data')
            ->assertDontSeeText('No data available')
            ->assertViewHas(['students' => $students])
            ->assertSee(' <thead>
            <tr>
                <th>#</th>
                <th>First-Name</th>
                <th>Last-Name</th>
                <th>Age</th>
                <th>Student No</th>
                <th>Level</th>
            </tr>
        </thead>')
            ->assertSee('   <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #686868;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>');
        $firstStudent = $students->first();
        $fields = ['first_name', 'last_name', 'age', 'level', 'student_no'];
        foreach ($fields as $field) {
            PDF::assertSeeText($firstStudent->$field);
        }
    })->only();

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
