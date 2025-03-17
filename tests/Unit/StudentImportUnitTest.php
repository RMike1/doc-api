<?php

use Mockery\MockInterface;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Services\Students\StudentService;
use App\Services\Students\Excel\StudentImport;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('queues student import', function () {
     Excel::fake();
     Storage::fake();
     $file = UploadedFile::fake()->create('students.xlsx');
    Excel::queueImport(new StudentImport, $file->getRealPath());
    
    Excel::assertQueued($file->getRealPath());
    $test= Excel::assertQueued($file->getRealPath(), function (StudentImport $import) {
        expect($import->chunkSize())->toBe(100);
        expect($import->startRow())->toBe(2);
        $rules = $import->rules();
        return true;
    });
    $this->assertDatabaseCount('students',0);
 });