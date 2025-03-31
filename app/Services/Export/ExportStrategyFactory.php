<?php

namespace App\Services\Export;

use App\Contracts\ExportStrategy;
use App\Services\Students\Pdf\StudentPdfExport;

class ExportStrategyFactory
{
    private const STRATEGIES = [
        'excel' => ExcelExportStrategy::class,
        'pdf' => PdfExportStrategy::class,
    ];

    public static function create(string $type): ExportStrategy
    {
        if (! isset(self::STRATEGIES[$type])) {
            throw new \Exception('Unsupported file type!');
        }

        $strategyClass = self::STRATEGIES[$type];

        if ($strategyClass === PdfExportStrategy::class) {
            return new $strategyClass(new StudentPdfExport);
        }

        return new $strategyClass;
    }
}
