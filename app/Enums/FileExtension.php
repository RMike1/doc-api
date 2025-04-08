<?php

namespace App\Enums;

enum FileExtension: string
{
    public function fileFormat(): string
    {
        return match ($this) {
            FileExtension::XLSX => \Maatwebsite\Excel\Excel::XLSX,
            FileExtension::CSV => \Maatwebsite\Excel\Excel::CSV,
            FileExtension::XLS => \Maatwebsite\Excel\Excel::XLS,
        };
    }

    public static function values(): array
    {
        return array_column(FileExtension::cases(), 'value');
    }

    case XLSX = 'xlsx';
    case CSV = 'csv';
    case XLS = 'xls';
}
