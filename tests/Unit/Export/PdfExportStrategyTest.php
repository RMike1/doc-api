<?php

use App\Services\Export\PdfExportStrategy;
use App\Services\Students\Pdf\StudentPdfExport;

beforeEach(function () {
    $this->pdfExport = $this->mock(StudentPdfExport::class);
    $this->strategy = new PdfExportStrategy($this->pdfExport);
});

test('generates pdf n returns success message', function () {
    $expectedResult = ['message' => 'PDF export started!', 'file_path' => 'exports/students.pdf'];
    $this->pdfExport->shouldReceive('generate')->once()->andReturn($expectedResult);
    
    $result = $this->strategy->export();
    
    expect($result)->toBe($expectedResult);
});

test('returns error message when export fails', function () {
    $this->pdfExport->shouldReceive('generate')->andThrow(new \Exception('Export failed'));
    
    $result = $this->strategy->export();
    
    expect($result)->toBe(['error' => 'PDF export failed. please try again...']);
});
