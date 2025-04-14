<?php

use App\Exceptions\AppException;
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

it('throws InvalidExportType exception for invalid file type', function () {
    expect(fn () => $this->strategy->create('docx'))->toThrow(AppException::invalidFileType());
});

it('can not throws InvalidExportType exception for valid file type', function () {
    expect(fn () => $this->strategy->create('pdf'))->not()->toThrow(AppException::invalidFileType());
});
