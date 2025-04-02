<?php

use App\Services\Export\ExcelExportStrategy;
use App\Services\Export\ExportStrategyFactory;
use App\Services\Export\PdfExportStrategy;

beforeEach(function () {
    $this->strategy = new ExportStrategyFactory;
});

it('creates excel strategy', function () {
    $strategy = $this->strategy->create('excel');
    expect($strategy)->toBeInstanceOf(ExcelExportStrategy::class);
});

it('creates pdf strategy', function () {
    $strategy = $this->strategy->create('pdf');
    expect($strategy)->toBeInstanceOf(PdfExportStrategy::class);
});

// it('thrws an error when there unsported file', function () {
//     $strategy = ExportStrategyFactory::create('pdfs');
//     expect($strategy)->toThrow(Exception::class, 'Unsupported file type!');
// });
