<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileExportRequest;
use App\Http\Requests\FileRequest;
use App\Services\Students\StudentService;

class StudentController extends Controller
{
    public function __construct(protected StudentService $studentService) {}

    public function export(FileExportRequest $req)
    {
        try {
            $this->studentService->export($req->validated('file_type'));

            return response()->json(['message' => 'Export started!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function download(FileExportRequest $req)
    {
        try {
            return $this->studentService->download($req->validated('file_type'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function import(FileRequest $req)
    {
        try {
            $this->studentService->import($req->file('file'));

            return response()->json(['message' => 'Import started!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
