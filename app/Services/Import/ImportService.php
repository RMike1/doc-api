<?php

namespace App\Services\Import;

use App\Enums\FileExtension;
use App\Services\Students\Excel\StudentImport;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ImportService
{
    public function importStudents(UploadedFile $file): array
    {
        $fileExtension = strtolower($file->getClientOriginalExtension());
        $fileType = FileExtension::tryFrom($fileExtension);

        return $fileType
            ? $this->queueImport($file, $fileType->fileFormat())
            : ['error' => 'Invalid file format. Only xlsx, csv, or xls are allowed.'];
    }

    private function queueImport($file, string $format): array
    {
        Excel::queueImport(new StudentImport, $file, null, $format);
        return ['message' => "Student data import is in progress with {$file->getClientOriginalExtension()} extension..."];
    }
}
