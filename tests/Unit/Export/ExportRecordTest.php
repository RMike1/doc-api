<?php

use App\Enums\ExportableType;
use App\Enums\ExportStatus;
use App\Enums\ExportType;
use App\Models\ExportRecord;
use App\Services\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new ExportService();
});

it('can creates excel export record', function (ExportType $exportType, ExportableType $exportableType) {
   $this->service->handle($exportType, $exportableType);


    expect(ExportRecord::first())
        ->type->toBe($exportableType->value)
        ->format->toBe($exportType->value)
        ->status->toBe(ExportStatus::SUCCESS)
        ->file_path->toContain('exports/export_')
        ->file_path->toContain('.xlsx');
})->with([
    [ExportType::EXCEL, ExportableType::STUDENT],
    [ExportType::EXCEL, ExportableType::TEACHER],
])->only();
