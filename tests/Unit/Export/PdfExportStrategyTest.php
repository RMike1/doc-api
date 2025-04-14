<?php

use App\Exceptions\AppException;
use App\Services\Export\PdfExportStrategy;
use App\Services\Students\Pdf\StudentPdfExport;

beforeEach(function () {
    $this->pdfExport = $this->mock(StudentPdfExport::class);
    $this->strategy = new PdfExportStrategy($this->pdfExport);
});

it('returns the generated PDF when export is successful', function () {
    $this->pdfExport->shouldReceive('generate')->once()->andReturn(true);
    $result = $this->strategy->export();
    expect(! $result)->toBe(true);
});

it('throws an exception if PDF export fails', function () {
    $this->pdfExport->shouldReceive('generate')->once()->andReturn(false);
    expect(fn () => $this->strategy->export())
        ->toThrow(AppException::exportFailed());
});
