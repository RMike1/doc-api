<?php

namespace App\Services;

use App\Enums\ExportableType;
use App\Enums\ExportStatus;
use App\Enums\ExportType;
use App\Exceptions\AppException;
use App\Models\ExportRecord;
use App\Services\Students\Excel\StudentExport;
use App\Services\Teachers\Excel\TeacherExport;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    public function handle(ExportType $exportType, ExportableType $exportableType): string
    {
        $fileTs = now()->format('YmdHis');
        $logId = $this->createExportRecord($exportType, $exportableType, $fileTs);

        return match ($exportType) {
            ExportType::EXCEL => $this->excelExport($exportableType, $logId, $fileTs),
            ExportType::PDF => $this->pdfExport($exportableType, $logId, $fileTs),
            default => throw AppException::invalidFileType(),
        };
    }

    private function excelExport(ExportableType $type, string $logId,string $fileTs)
    {
        $filePath = "exports/export_{$fileTs}_{$type->value}.xlsx";

        $export = match ($type) {
            ExportableType::STUDENT => new StudentExport($logId),
            ExportableType::TEACHER => new TeacherExport($logId),
            default => throw AppException::invalidExportType(),
        };

        $export = Excel::queue($export, $filePath);
        throw_unless($export, AppException::exportFailed());

        return $filePath;
    }

    private function pdfExport(ExportableType $type, string $logId, string $fileTs)
    {
        $filePath = "exports/export_{$fileTs}_{$type->value}.pdf";
        $data = $this->getData($type, $logId);

        $pdf = SnappyPdf::loadView("exports.{$type->value}",  ['data' => $data]);
        $pdf->save(Storage::disk('local')->path($filePath));

        return $filePath;
    }

    private function createExportRecord(ExportType $exportType, ExportableType $exportableType,string $fileTs): string
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

    private function getData(ExportableType $type, string $logId): array
    {
        $export = match ($type) {
            ExportableType::STUDENT => new StudentExport($logId),
            ExportableType::TEACHER => new TeacherExport($logId),
            default => throw AppException::invalidExportType("Invalid export type: {$type->value}")
        };

        return $export->query()->get()->toArray();
    }
}