<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileExportRequest;
use App\Http\Requests\FileRequest;
use App\Services\Students\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class SchoolController extends Controller
{
    public function __construct(protected StudentService $studentService) {}

    // ========================Export Students data==========================

    public function export(FileExportRequest $req): JsonResponse
    {
        $this->studentService->export($req->validated('file_type'));

        return response()->json(['message' => 'Export started!'], 200);
    }

    // ========================Import Students data==========================

    public function import(FileRequest $req): JsonResponse
    {
        $this->studentService->import($req->file('file'));

        return response()->json(['message' => 'Import started!'], 200);
    }

    // ========================Fetch Students data==========================

    public function exports(Request $req): JsonResponse
    {
        $export_records = $this->studentService->exports();

        return response()->json(['data' => $export_records], 200);
    }

    // ========================Download Students data==========================

    public function download($file)
    {
        return $this->studentService->downloadFile($file);
    }
}
