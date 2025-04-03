<?php

namespace App\Http\Controllers\Student;

use App\Exceptions\InvalidExportTypeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileExportRequest;
use App\Http\Requests\FileRequest;
use App\Services\Students\StudentService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentController extends Controller
{
    public function __construct(protected StudentService $studentService) {}

    public function export(FileExportRequest $req): JsonResponse
    {
        // dd($req);
        try {
            $this->studentService->export($req->file_type);

            return response()->json(['message' => 'Export started!'], 200);
        } catch (InvalidExportTypeException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function import(FileRequest $req): JsonResponse
    {
        try {
            $this->studentService->import($req->file('file'));

            return response()->json(['message' => 'Import started!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function download(FileExportRequest $req): BinaryFileResponse|JsonResponse
    {
        try {
            return $this->studentService->download($req->validated('file_type'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
