<?php

namespace App\Services\Export;

use App\Contracts\ExportStrategy;
use App\Enums\ExportType;
use App\Exceptions\InvalidExportTypeException;
use App\Services\Students\Pdf\StudentPdfExport;

class ExportStrategyFactory
{
    public function create(string $fileType): ExportStrategy
    {
        $type = ExportType::tryFrom($fileType) ?? throw new InvalidExportTypeException;

        return match ($type) {
            ExportType::EXCEL => new ExcelExportStrategy,
            ExportType::PDF => new PdfExportStrategy(new StudentPdfExport),
            default => throw new InvalidExportTypeException,
        };
    }
}
