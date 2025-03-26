<?php

use App\Models\Student;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Excel as ExcelType;
use App\Services\Students\Excel\StudentImport;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
});

it('queues excel import with correct configs', function () {
    Excel::fake();
    $file = UploadedFile::fake()->create('students.xlsx');
    Excel::queueImport(new StudentImport, $file->getRealPath());
    Excel::assertQueued($file->getRealPath(), function (StudentImport $import) {
        expect($import->chunkSize())->toBe(1000)
            ->and($import->startRow())->toBe(2);
            
        $rules = $import->rules();
        expect($rules)->toBeArray()
            ->and($rules)->toHaveKey('*.0') 
            ->and($rules)->toHaveKey('*.1') 
            ->and($rules)->toHaveKey('*.2') 
            ->and($rules)->toHaveKey('*.3') 
            ->and($rules)->toHaveKey('*.4');
            
        return true;
    });
});


it('starts from second row', function () {
    $import = new StudentImport();
    expect($import->startRow())->toBe(2);
});


it('processes data in chunks', function () {
    $import = new StudentImport();
    expect($import->chunkSize())->toBe(1000);
});


// it('imports students from excel file into database', function () {
//     Storage::fake('local');
//     $students = Student::factory(5)->make();
//     $testData = [
//         ['first_name', 'last_name', 'age', 'student_no', 'level'], 
//     ];

//     foreach ($students as $student) {
//         $testData[] = [
//             $student->first_name,
//             $student->last_name,
//             $student->age,
//             $student->student_no,
//             $student->level
//         ];
//     }
    
//     $filename = 'students.xlsx';
//     Storage::disk('local')->put($filename, '');
//     $filePath = Storage::disk('local')->path($filename);
    
//     $mockData = \Mockery::mock(FromArray::class);;
//     $mockData->shouldReceive('array')->once()->andReturn($testData);
//     Excel::store($mockData, $filename, 'local', ExcelType::XLSX);
    
//     $import = new StudentImport();
//     Excel::queueImport($import, $filePath);
    
//     $this->assertDatabaseCount('students', 5);
    
//     $firstStudent = $students->first();
    
//     $this->assertDatabaseHas('students', [
//         'first_name' => $firstStudent->first_name,
//         'last_name' => $firstStudent->last_name,
//         'student_no' => $firstStudent->student_no
//     ]);
    
// });