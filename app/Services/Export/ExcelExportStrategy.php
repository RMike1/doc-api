<?php

namespace App\Services\Export;

use App\Enums\ExportStatus;
use App\Models\ExportRecord;
use Illuminate\Support\Carbon;
use App\Contracts\ExportStrategy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exceptions\ExportFailedException;
use App\Services\Students\Excel\StudentExport;

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
        
        if (!$export) {
            $log->update(['status' => ExportStatus::FAILED]);
            throw new ExportFailedException("Failed to queue export job");
        }
    }
}