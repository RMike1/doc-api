<?php

namespace App\Services\Students;

use App\Http\Resources\ExportRecordResource;
use App\Models\ExportRecord;
use App\Models\Student;
use App\Services\Export\ExportStrategyFactory;
use App\Services\Import\ImportService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StudentService
{
    public function __construct(private ImportService $importService, private ExportStrategyFactory $exportStrategyFactory) {}

    // -------------------export student data----------------------

    public function export(string $fileType): void
    {
        $strategy = $this->exportStrategyFactory->create($fileType);
        $strategy->export();
    }

    // -------------------import student data----------------------

    public function import(UploadedFile $file): void
    {
        $this->importService->importStudents($file);
    }

    // -------------------get student data----------------------
    public function exports()
    {
        return ExportRecordResource::collection(ExportRecord::latest()->get());
        // return ExportRecord::latest()->get()->toResourceCollection();
    }

    // -------------------Download Option---------------------

    public function downloadFile($file)
    {
        $file = ExportRecord::find($file);
        throw_unless($file, new \Exception('Record not found!'));
        throw_unless(Storage::disk('local')->exists($file->file_path), new \Exception('File not found!'));

        return Storage::download($file->file_path);
    }
}
