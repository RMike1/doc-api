<?php

namespace App\Services\Export;

use App\Contracts\ExportStrategy;
use App\Enums\ExportStatus;
use App\Exceptions\AppException;
use App\Models\ExportRecord;
use App\Services\Students\Excel\StudentExport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportStrategy implements ExportStrategy
{
    public function export(): void
    {
        $name = Carbon::now()->format('YmdHis');
        $filePath = "exports/students_{$name}.xlsx";

        DB::transaction(function () use ($filePath) {
            $log = $this->createExportRecord($filePath);
            $this->queueExport($log, $filePath);
        });
    }

    private function createExportRecord(string $filePath): ExportRecord
    {
        return ExportRecord::create([
            'file_path' => $filePath,
            'status' => ExportStatus::PROCESSING,
        ]);
    }

    private function queueExport(ExportRecord $log, string $filePath): void
    {
        $export = Excel::queue(new StudentExport($log->id), $filePath);
        throw_unless($export, AppException::exportFailed());
    }
}
