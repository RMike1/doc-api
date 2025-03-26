<?php

namespace App\Services\Students;

use App\Services\Export\ExportStrategyFactory;
use App\Services\Import\ImportService;
use Illuminate\Http\UploadedFile;
use App\Models\Student;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Students\Excel\StudentExport;

class StudentService
{

    public function __construct(private ImportService $importService){}

    //-------------------export student data----------------------

    public function export(string $fileType): array
    {
        try {
            $strategy = ExportStrategyFactory::create($fileType);
            return $strategy->export();  
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    //-------------------import student data----------------------

    public function import(UploadedFile $file): array
    {
        return $this->importService->importStudents($file);
    }


    //-------------------Download Option---------------------

    public function download(string $fileType)
    {
        try {
            $name = now()->format('YmdHis');
    
            if ($fileType === 'excel') {
                $fileName = "students_{$name}.xlsx";
                return Excel::download(new StudentExport, $fileName);
            } 
            
            if ($fileType === 'pdf') {
                $students = Student::select('first_name', 'last_name', 'age', 'student_no', 'level')->get();
                
                if ($students->isEmpty()) {
                    return response()->json(['error' => 'No data to export!'], 400);
                }
                $pdf = \PDF::loadView('export-pdf', ['students' => $students]);
                return $pdf->download("students_{$name}.pdf");
            }
    
            return response()->json(['error' => 'Unsupported file type!'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Export failed. Please try again...'], 500);
        }
    }
    
}