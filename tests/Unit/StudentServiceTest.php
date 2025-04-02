<?php

use App\Contracts\ExportStrategy;
use App\Services\Export\ExportStrategyFactory;
use App\Services\Import\ImportService;
use App\Services\Students\StudentService;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    $this->strategyMock = $this->mock(ExportStrategy::class);
    $this->importService = $this->mock(ImportService::class);
    $this->exportStrategyFactory = $this->mock(ExportStrategyFactory::class);
    $this->service = new StudentService($this->importService, $this->exportStrategyFactory);
    Excel::fake();
});

it('calls the correct export strategy', function (string $fileType) {
    $this->exportStrategyFactory
        ->shouldReceive('create')
        ->with($fileType)
        ->andReturn($this->strategyMock);
    $this->strategyMock->shouldReceive('export')->once();
    $this->service->export($fileType);
    $this->strategyMock->shouldHaveReceived('export')->once();
})->with(['excel', 'pdf']);

describe('import students data', function () {
    it('calls to import service', function () {
        $file = UploadedFile::fake()->create('students.xlsx');
        $this->importService
            ->shouldReceive('importStudents')
            ->with($file)
            ->once();
        $this->service->import($file);
    });
});
