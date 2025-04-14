<?php

namespace App\Services\Import;

use App\Enums\FileExtension;
use App\Exceptions\AppException;
use App\Services\Students\Excel\StudentImport;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ImportService
{
    public function importStudents(UploadedFile $file): void
    {
        $fileExtension = mb_strtolower($file->getClientOriginalExtension());
        $fileType = FileExtension::tryFrom($fileExtension);
        throw_if(! $fileType, AppException::invalidFileType('Invalid file format. Only xlsx, csv, or xls are allowed...'));
        $this->queueImport($file, $fileType->fileFormat());
    }

    private function queueImport($file, string $format): void
    {
        Excel::queueImport(new StudentImport, $file, null, $format);
    }
}
