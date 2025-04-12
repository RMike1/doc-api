<?php

namespace App\Http\Controllers\Student;

use App\Exceptions\ExportFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileExportRequest;
use App\Http\Requests\FileRequest;
use App\Services\Students\StudentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(protected StudentService $studentService) {}

    public function export(FileExportRequest $req): JsonResponse
    {
        try {
            $export = $this->studentService->export($req->validated('file_type'));

            return response()->json(['message' => 'Export started!'], 200);
        } catch (ExportFailedException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function import(FileRequest $req): JsonResponse
    {
        try {
            $this->studentService->import($req->validated(file('file')));

            return response()->json(['message' => 'Import started!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function exports(Request $req): JsonResponse
    {
        try {
            $export_records = $this->studentService->exports();

            return response()->json(['data' => $export_records], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function download($file)
    {
        try {
            return $this->studentService->downloadFile($file);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
