<?php

namespace App\Services\Export;

use App\Enums\ExportableType;
use App\Exceptions\AppException;
use App\Services\Base\BaseExport;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Storage;
use App\Services\Students\Excel\StudentExport;
use App\Services\Teachers\Excel\TeacherExport;

class PdfExport extends BaseExport
{
    public function export(): string
    {
        $filePath = $this->getFilePath();
        $data = $this->getData();

        $pdf = SnappyPdf::loadView("exports.{$this->type->value}", ['data' => $data]);
        $pdf->save(Storage::disk('local')->path($filePath));

        return $filePath;
    }

    private function getData(): array
    {
        $export = match ($this->type) {
            ExportableType::STUDENT => new StudentExport($this->logId),
            ExportableType::TEACHER => new TeacherExport($this->logId),
            default => throw AppException::invalidExportType("Invalid export type: {$this->type->value}")
        };

        return $export->query()->get()->toArray();
    }

    protected function getExtension(): string
    {
        return 'pdf';
    }
}