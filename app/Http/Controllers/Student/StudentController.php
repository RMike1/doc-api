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
        return Excel::download(new StudentExport, 'students.xlsx');
    }
}
