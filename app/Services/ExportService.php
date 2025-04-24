<?php

namespace App\Services;

use App\Enums\ExportableType;
use App\Enums\ExportStatus;
use App\Enums\ExportType;
use App\Exceptions\AppException;
use App\Models\ExportRecord;
use App\Services\Export\ExcelExport;
use App\Services\Export\PdfExport;
use Illuminate\Support\Facades\DB;

class ExportService
{
    public function handle(ExportType $exportType, ExportableType $exportableType): string
    {
        $fileTs = now()->format('YmdHis');
        $logId = $this->createExportRecord($exportType, $exportableType, $fileTs);

        return match ($exportType) {
            ExportType::EXCEL => (new ExcelExport($logId, $fileTs, $exportableType))->export(),
            ExportType::PDF => (new PdfExport($logId, $fileTs, $exportableType))->export(),
            default => throw AppException::invalidFileType(),
        };
    }

    private function createExportRecord(ExportType $exportType, ExportableType $exportableType, string $fileTs): string
    {
        return DB::transaction(function () use ($exportType, $exportableType, $fileTs) {
            $filePath = "exports/export_{$fileTs}_{$exportableType->value}.{$exportType->getExtension()}";
    
            $record = ExportRecord::create([
                'type' => $exportableType->value,
                'format' => $exportType->value,
                'status' => ExportStatus::PROCESSING,
                'file_path' => $filePath,
            ]);

            return $record->id;
        });
    }
}