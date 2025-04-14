<?php

namespace App\Services\Students\Pdf;

use App\Exceptions\AppException;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class StudentPdfExport
{
    public function generate(): bool
    {
        $students = Student::select('first_name', 'last_name', 'age', 'student_no', 'level')->get();
        throw_if(empty($students), AppException::couldNotFindData());
        $pdf = \PDF::loadView('export-pdf', ['students' => $students]);
        $name = now()->format('YmdHis');
        $filePath = Storage::disk('local')->path("exports/students_{$name}.pdf");
        $pdf->save($filePath);

        return true;
    }
}
