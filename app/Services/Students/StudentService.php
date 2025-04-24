<?php

namespace App\Services\Students;

use App\Enums\ExportStatus;
use App\Enums\ExportType;
use App\Exceptions\AppException;
use App\Models\ExportRecord;
use App\Models\Student;
use App\Services\Export\ExcelExportStrategy;
use App\Services\Export\PdfExportStrategy;
use App\Services\Import\ImportService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentService
{
    public function __construct(private ImportService $importService) {}

    // -------------------export student data----------------------

    public function export(string $fileType)
    {

        $type = ExportType::tryFrom($fileType) ?? throw AppException::invalidFileType();

        $strategy = match ($type) {
            ExportType::EXCEL => new ExcelExportStrategy,
            ExportType::PDF => new PdfExportStrategy,
            default => throw AppException::invalidFileType(),
        };

        return $strategy->export();
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
