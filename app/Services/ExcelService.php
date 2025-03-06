<?php

namespace App\Services;
use App\Exports\StudentExport;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelService
{
    public function export()
    {
        $fileName='students.xlsx';
        (new StudentExport)->store($fileName);
    }
    public function import($file)
    {
        Excel::queueImport(new StudentImport, $file, null, \Maatwebsite\Excel\Excel::XLSX);
    }
}