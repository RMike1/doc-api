<?php

namespace App\Http\Controllers\Student;
use Illuminate\Http\Request;
use App\Services\ExcelService;
use App\Services\ExcelStudent;
use App\Http\Requests\FileRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileExportRequest;
use App\Services\Students\StudentService;

class StudentController extends Controller
{
    public function __construct(protected StudentService $studentService)
    {
    }

    public function export(FileExportRequest $req)
    {
        $export=$this->studentService->export($req->file_type);
        return isset($export['error'])
        ? response()->json(['error' => $export['error']], 500)
        : response()->json(['message'=>$export['message']],200);
    }

    public function import(FileRequest $req)
{
    $import = $this->studentService->import($req->file('file'));

    return !empty($import['error'])
        ? response()->json(['error' => $import['error']], 422)
        : response()->json(['message' => $import['message']], 200);
}

}
