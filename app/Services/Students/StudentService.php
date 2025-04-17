<?php

namespace App\Services\Students;

use App\Enums\ExportStatus;
use App\Exceptions\AppException;
use App\Models\ExportRecord;
use App\Models\Student;
use App\Services\Export\ExportStrategyFactory;
use App\Services\Import\ImportService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    // -------------------Fetching Students data----------------------
    public function exports(): ResourceCollection
    {
        return ExportRecord::latest()->get()->toResourceCollection();
    }

    // -------------------Download Option---------------------

    public function downloadFile($file): StreamedResponse
    {
        $file = ExportRecord::find($file);
        throw_unless($file, AppException::recordNotFound());
        throw_if($file->status !== ExportStatus::SUCCESS, AppException::fileNotFound());
        throw_unless(Storage::disk('local')->exists($file->file_path), AppException::fileNotFound());

        return Storage::download($file->file_path);
    }
}
