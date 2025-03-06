<?php

namespace App\Services\Students;

use App\Enums\FileExtension;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Students\Excel\StudentExcel;

class StudentService
{
    public function export($file_type)
    {
        if($file_type==='excel'){
            dd('hey excel');    
            $fileName = 'students.xlsx';
            (new StudentExcel)->store($fileName);
        }elseif($file_type==='pdf'){
            dd('hey pdf');    
        }else{
            return [
                'file not supported!'
            ];
        }
    }
    public function import($file): array
    {
        $fileExtension = strtolower($file->getClientOriginalExtension());
        $fileType = FileExtension::tryFrom($fileExtension);

        return $fileType
            ? $this->queueImport($file, $fileType->fileFormat())
            : ['error' => 'Invalid file format. Only xlsx, csv, or xls are allowed.'];
    }

    private function queueImport($file, string $format): array
    {
        Excel::queueImport(new StudentExcel, $file, null, $format);
        return ['message' => "Student data import is in progress with {$file->getClientOriginalExtension()} extension..."];
    }
}