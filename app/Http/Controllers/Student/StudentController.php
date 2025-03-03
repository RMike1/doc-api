<?php

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use Illuminate\Http\Request;
use App\Services\ExcelService;

class StudentController extends Controller
{


    protected ExcelService $excel;

    public function __construct(ExcelService $excel)
    {
        $this->excel=$excel;    
    }

    public function export() 
    {
        
        $excel_export=$this->excel->export();
        
        return response()->json('Export started...');
    }
    
    public function import(FileRequest $req) 
    {

        $excel_import=$this->excel->import($req->file('file'));
        
        return response()->json('Student data import in progress...');
    }
}
