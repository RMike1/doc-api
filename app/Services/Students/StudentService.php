<?php

namespace App\Services\Students;

use App\Models\Student;
use App\Models\ExportRecord;
use App\Exceptions\AppException;
use Illuminate\Http\UploadedFile;
use App\Services\Import\ImportService;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\FileNotFoundException;
use App\Exceptions\RecordNotFoundException;
use App\Http\Resources\ExportRecordResource;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Export\ExportStrategyFactory;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
        throw_unless(Storage::disk('local')->exists($file->file_path), AppException::fileNotFound());
        return Storage::download($file->file_path);
    }
}
