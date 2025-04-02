<?php

namespace App\Services\Export;

use App\Contracts\ExportStrategy;
use App\Services\Students\Excel\StudentExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportStrategy implements ExportStrategy
{
    public function export(): void
    {
        $name = Carbon::now()->format('YmdHis');
        $filePath = "exports/students_{$name}.xlsx";
        Excel::queue(new StudentExport, $filePath);
    }
}
