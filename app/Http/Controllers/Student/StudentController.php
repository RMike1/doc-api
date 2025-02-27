<?php

namespace App\Http\Controllers\Student;
use App\Exports\StudentExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function export() 
    {
        $fileName='students.xlsx';
        // return Excel::download(new StudentExport, $fileName);
        (new StudentExport)->store($fileName);
        return response()->json('Export started..');
    }
}
