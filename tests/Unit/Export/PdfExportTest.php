<?php

use App\Enums\ExportableType;
use App\Services\Export\PdfExport;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);

it('exports pdf file with correct path', function () {
    Storage::fake('local');
    SnappyPdf::fake();
    SnappyPdf::shouldReceive('loadView')
        ->once()
        ->andReturnSelf();
    SnappyPdf::shouldReceive('save')
        ->once();

    $timestamp = now()->format('YmdHis');
    $exporter = new PdfExport('test-id', $timestamp, ExportableType::TEACHER);

    $result = $exporter->export();
    $expectedPath = "exports/export_{$timestamp}_teacher.pdf";

    expect($result)->toBe($expectedPath);
});