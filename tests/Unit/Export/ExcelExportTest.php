<?php

use App\Enums\ExportableType;
use App\Services\Export\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;

it('exports excel file with correct path', function () {
    Excel::fake();
    $fileTs = now()->format('YmdHis');
    $exporter = new ExcelExport('export_record_id', $fileTs, ExportableType::STUDENT);
    $result = $exporter->export();
    $expectedPath = "exports/export_{$fileTs}_student.xlsx";
    expect($result)->toBe($expectedPath);
    Excel::assertQueued($expectedPath);
});