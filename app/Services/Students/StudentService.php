<?php

namespace App\Services\Students;

use App\Models\Student;
use App\Enums\FileExtension;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Services\Students\Excel\StudentExport;
use App\Services\Students\Excel\StudentImport;

class StudentService
{

    //-------------------export student data----------------------

    public function export($file_type)
    {
        if ($file_type === 'excel') {   
            $name = now()->format('YmdHis');
            $filePath = "exports/employees_{$name}.xlsx";
            (new StudentExport)->store($filePath);

        } elseif ($file_type === 'pdf') {
            $students=Student::select('first_name', 'last_name', 'age', 'student_no', 'level')->get();
                    $pdf = \PDF::loadView('export-pdf', 
                        [
                            'students'=>$students
                        ]);
                    $name = now()->format('YmdHis');
                    $filePath = Storage::disk('local')->path("exports/employees_{$name}.pdf");
                    $fileUrlPdf = url($filePath);
                    $pdf->save($filePath);
        } else {
            return [
                'unsupported file!'
            ];
        }
    }


    //-------------------import student data----------------------

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
        Excel::queueImport(new StudentImport, $file, null, $format);
        return ['message' => "Student data import is in progress with {$file->getClientOriginalExtension()} extension..."];
    }
}