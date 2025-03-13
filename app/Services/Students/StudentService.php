<?php

namespace App\Services\Students;

use App\Models\Student;
use App\Enums\FileExtension;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Services\Students\Excel\StudentExport;
use App\Services\Students\Excel\StudentImport;

class StudentService
{

    //-------------------export student data----------------------

    public function export(string $file_type): array
    {
        if ($file_type === 'excel') {
            $name = now()->format('YmdHis');
            $filePath = "exports/employees_{$name}.xlsx";
            (new StudentExport)->store($filePath);
            return ['message' => 'Export started...'];

            
        } elseif ($file_type === 'pdf') {
            $students = Student::select('first_name', 'last_name', 'age', 'student_no', 'level')->get();
            if ($students->isNotEmpty()) {
                $pdf = \PDF::loadView(
                    'export-pdf',
                    [
                        'students' => $students
                    ]
                );
                $name = now()->format('YmdHis');
                $filePath = Storage::disk('local')->path("exports/employees_{$name}.pdf");
                $fileUrlPdf = url($filePath);
                $pdf->save($filePath);
                return ['message' => 'Export started...', 'file_path' => $filePath];
            } else {
                return [
                    'message' => 'No data to export!'
                ];
            }
        } else {
            return [
                'error' => 'unsupported file!'
            ];
        }
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
