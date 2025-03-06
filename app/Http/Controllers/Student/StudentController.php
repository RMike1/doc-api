<?php

namespace App\Http\Controllers\Student;
use Illuminate\Http\Request;
use App\Services\ExcelService;
use App\Services\ExcelStudent;
use App\Http\Requests\FileRequest;
use App\Http\Controllers\Controller;
use App\Services\Students\StudentService;

class StudentController extends Controller
{
    public function __construct(protected StudentService $excel)
    {
    }

    public function export()
    {

        $this->excel->export();
        return response()->json(['message' => 'Export started...']);
    }

    public function import(FileRequest $req)
    {
        $import=$this->excel->import($req->file('file'));
        if (!empty($import['error'])) { 
            return response()->json(['error'=>$import['error']], 422);
        }
        return response()->json(['message'=>$import['message']], 200);
    }
}
