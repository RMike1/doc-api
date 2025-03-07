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
    public function __construct(protected StudentService $excel)
    {
    }

    public function export(FileExportRequest $req)
    {
        $export=$this->excel->export($req->file_type);
        return response()->json(['message' => 'Export started...']);
    }

    public function import(FileRequest $req)
{
    $import = $this->excel->import($req->file('file'));

    return !empty($import['error'])
        ? response()->json(['error' => $import['error']], 422)
        : response()->json(['message' => $import['message']], 200);
}

}
