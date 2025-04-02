<?php

namespace App\Enums;

enum FileExtension: string
{
    public function fileFormat(): string
    {
        return match ($this) {
            self::XLSX => \Maatwebsite\Excel\Excel::XLSX,
            self::CSV => \Maatwebsite\Excel\Excel::CSV,
            self::XLS => \Maatwebsite\Excel\Excel::XLS,
        };
    }
    case XLSX = 'xlsx';
    case CSV = 'csv';
    case XLS = 'xls';
}