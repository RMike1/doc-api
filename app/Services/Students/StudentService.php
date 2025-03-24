<?php

namespace App\Services\Students;

use App\Services\Export\ExportStrategyFactory;
use App\Services\Import\ImportService;
use Illuminate\Http\UploadedFile;

class StudentService
{

    public function __construct(private ImportService $importService){}

    //-------------------export student data----------------------

    public function export(string $fileType): array
    {
        try {
            $strategy = ExportStrategyFactory::create($fileType);
            return $strategy->export();  
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    //-------------------import student data----------------------

    public function import(UploadedFile $file): array
    {
        return $this->importService->importStudents($file);
    }
}