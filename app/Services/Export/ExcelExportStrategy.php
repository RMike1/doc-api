<?php

namespace App\Services\Export;

use App\Contracts\ExportStrategy;
use App\Services\Students\Excel\StudentExport;
use Exception;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportStrategy implements ExportStrategy
{
    public function export(): array
    {
        try {
            $name = Carbon::now()->format('YmdHis');
            $filePath = "exports/students_{$name}.xlsx";
            Excel::queue(new StudentExport, $filePath);
            return ['message' => 'Excel export started!'];
        } catch (Exception $e) {
            return ['error' => 'Excel export failed. please try again...'];
        }
    }
}
