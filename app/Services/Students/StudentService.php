<?php

namespace App\Services\Students;

use App\Models\Student;
use App\Services\Export\ExportStrategyFactory;
use App\Services\Import\ImportService;
use App\Services\Students\Excel\StudentExport;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class StudentService
{
    public function __construct(private ImportService $importService) {}

    // -------------------export student data----------------------

    public function export(string $fileType): array
    {
        try {
            $strategy = ExportStrategyFactory::create($fileType);

            return $strategy->export();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // -------------------import student data----------------------

    public function import(UploadedFile $file): array
    {
        return $this->importService->importStudents($file);
    }

    // -------------------Download Option---------------------

    public function download(string $fileType)
    {
        $name = now()->format('YmdHis');

        if ($fileType === 'excel') {
            $fileName = "students_{$name}.xlsx";

            return Excel::download(new StudentExport, $fileName);
        } elseif ($fileType === 'pdf') {
            $students = Student::select('first_name', 'last_name', 'age', 'student_no', 'level')->get();
            throw_if($students->isEmpty(), new \Exception('No data to export!'));

            $pdf = \PDF::loadView('export-pdf', ['students' => $students]);

            return $pdf->download("students_{$name}.pdf");
        } else {
            throw new \Exception('Unsupported file type!');
        }
    }
}
