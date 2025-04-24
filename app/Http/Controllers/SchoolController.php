<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileExportRequest;
use App\Http\Requests\FileRequest;
use App\Services\ExportService;
use App\Services\Students\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct(protected StudentService $studentService,
        protected ExportService $exportService) {}

    // ========================Export Students data==========================

    public function export(FileExportRequest $request): JsonResponse
    {
        $filePath = $this->exportService->handle(
            $request->getExportType(),
            $request->getExportableType()
        );

        return response()->json([
            'message' => 'Export Started!',
        ], 200);
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
