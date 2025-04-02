<?php

namespace App\Services\Export;

use App\Contracts\ExportStrategy;
use App\Enums\ExportType;
use App\Services\Students\Pdf\StudentPdfExport;

class ExportStrategyFactory
{
    public function create(string $fileType): ExportStrategy
    {
        $type = ExportType::tryFrom($fileType) ?? throw new \Exception('Invalid export type.');

        return match ($type) {
            ExportType::EXCEL => new ExcelExportStrategy,
            ExportType::PDF => new PdfExportStrategy(new StudentPdfExport),
            default => throw new \Exception('Unsupported export type.'),
        };
    }

    // public static function create(string $type): ExportStrategy
    // {
    //     throw_if(! isset(self::STRATEGIES[$type]), new \Exception('Unsupported file type!'));
    //     $strategyClass = self::STRATEGIES[$type];
    //     if ($strategyClass === PdfExportStrategy::class) {
    //         return new $strategyClass(new StudentPdfExport);
    //     }

    //     return new $strategyClass;
    // }

    // private const STRATEGIES = [
    //     'excel' => ExcelExportStrategy::class,
    //     'pdf' => PdfExportStrategy::class,
    // ];
}
