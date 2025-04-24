<?php

namespace App\Services\Export;

use App\Enums\ExportableType;
use App\Exceptions\AppException;
use App\Services\Base\BaseExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Students\Excel\StudentExport;
use App\Services\Teachers\Excel\TeacherExport;

class ExcelExport extends BaseExport
{
    public function export(): string
    {
        $filePath = $this->getFilePath();
        
        $export = match ($this->type) {
            ExportableType::STUDENT => new StudentExport($this->logId),
            ExportableType::TEACHER => new TeacherExport($this->logId),
            default => throw AppException::invalidExportType(),
        };

        $export = Excel::queue($export, $filePath);
        throw_unless($export, AppException::exportFailed());

        return $filePath;
    }

    protected function getExtension(): string
    {
        return 'xlsx';
    }
}