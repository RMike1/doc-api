<?php

use App\Services\Import\ImportService;
use App\Services\Students\StudentService;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    $this->importService = $this->mock(ImportService::class);
    $this->service = new StudentService($this->importService);
    Excel::fake();
});

describe('export', function () {
    it('delegates to correct export strategy', function () {
        $result = $this->service->export('excel');
        expect($result)->toHaveKey('message')
            ->and($result['message'])->toBe('Excel export started!');
    });

    it('handles invalid export type', function () {
        $result = $this->service->export('word');
        expect($result)->toBe(['error' => 'Unsupported file type!']);
    });
});

describe('import', function () {
    it('delegates to import service', function () {
        $file = UploadedFile::fake()->create('students.xlsx');
        $expectedResult = ['message' => 'Import started'];

        $this->importService
            ->shouldReceive('importStudents')
            ->with($file)
            ->once()
            ->andReturn($expectedResult);

        $result = $this->service->import($file);
        expect($result)->toBe($expectedResult);
    });
});
