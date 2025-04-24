<?php

namespace App\Services\Export;

use App\Contracts\ExportStrategy;
use App\Exceptions\AppException;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class PdfExportStrategy implements ExportStrategy
{
    public function export(): void
    {
        $students = Student::select('first_name', 'last_name', 'age', 'student_no', 'level')->get();
        throw_if(empty($students), AppException::couldNotFindData());
        $pdf = \PDF::loadView('export-pdf', ['students' => $students]);
        $name = now()->format('YmdHis');
        $filePath = Storage::disk('local')->path("exports/students_{$name}.pdf");
        $pdf->save($filePath);
    }
}
