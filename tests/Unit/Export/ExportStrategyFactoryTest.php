<?php

use App\Services\Export\ExcelExportStrategy;
use App\Services\Export\ExportStrategyFactory;
use App\Services\Export\PdfExportStrategy;

it('creates excel strategy', function () {
    $strategy = ExportStrategyFactory::create('excel');
    expect($strategy)->toBeInstanceOf(ExcelExportStrategy::class);
});

it('creates pdf strategy', function () {
    $strategy = ExportStrategyFactory::create('pdf');
    expect($strategy)->toBeInstanceOf(PdfExportStrategy::class);
});
