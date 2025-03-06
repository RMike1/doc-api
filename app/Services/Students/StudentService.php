<?php

namespace App\Services\Students;

use Maatwebsite\Excel\Facades\Excel;
use App\Services\Students\Excel\StudentExcel;

class StudentService
{
    public function export()
    {
        $fileName = 'students.xlsx';
        (new StudentExcel)->store($fileName);
    }

    public function import($file)
    {
        $fileExtension = strtolower($file->getClientOriginalExtension());
        if($fileExtension==='xlsx'){
            Excel::queueImport(new StudentExcel, $file, null, \Maatwebsite\Excel\Excel::XLSX);
            return ['message'=>'Student data import is in progress with '.$fileExtension.' extension...'];
        }
        elseif($fileExtension==='csv'){
            Excel::queueImport(new StudentExcel, $file, null, \Maatwebsite\Excel\Excel::CSV);
            return ['message'=>'Student data import is in progress with '.$fileExtension.' extension...'];
        }
        elseif($fileExtension==='xls'){
            Excel::queueImport(new StudentExcel, $file, null, \Maatwebsite\Excel\Excel::XLS);
            return ['message'=>'Student data import is in progress with '.$fileExtension.' extension...'];
        }else{
            return ['error' => 'Invalid file format. Only xlsx or csv are allowed.'];
        }
    }
}