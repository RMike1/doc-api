<?php

namespace App\Services\Students;

use App\Models\Student;
use App\Enums\FileExtension;
use Illuminate\Http\UploadedFile;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Services\Students\Excel\StudentExport;
use App\Services\Students\Excel\StudentImport;
use App\Services\Students\Pdf\StudentPdfExport;

class StudentService
{

    //-------------------export student data----------------------

    public function export(string $fileType): array
    {
        if ($fileType === 'excel') {
            try {
                $name = now()->format('YmdHis');
                $filePath = "exports/students_{$name}.xlsx";
                
                (new StudentExport)->store($filePath);
                return ['message' => 'Excel export started!'];
            } catch (Exception $e) {
                // Log::error($e->getMessage());
                return ['error' => 'Excel export failed. please try again...'];
            }
        } elseif ($fileType === 'pdf') {
            try{
                return (new StudentPdfExport())->generate(); 
            }catch(Exception $e){
                // Log::error($e->getMessage());
                return ['error' => 'Pdf export failed. please try again...'];
            }
        }
        return ['error' => 'Unsupported file type!'];
    }

    //-------------------import student data----------------------

    public function import(UploadedFile $file): array
    {
        $fileExtension = strtolower($file->getClientOriginalExtension());
        $fileType = FileExtension::tryFrom($fileExtension);

        return $fileType
            ? $this->queueImport($file, $fileType->fileFormat())
            : ['error' => 'Invalid file format. Only xlsx, csv, or xls are allowed.'];
    }

    private function queueImport($file, string $format): array
    {
        Excel::queueImport(new StudentImport, $file, null, $format);
        return ['message' => "Student data import is in progress with {$file->getClientOriginalExtension()} extension..."];
    }
}
