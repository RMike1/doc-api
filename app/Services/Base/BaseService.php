<?php

namespace App\Services\Base;

use App\Models\ExportRecord;
use App\Exceptions\AppException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class BaseService
{
    abstract protected function getExportClass();
    abstract protected function getImportClass();
    abstract protected function getModelClass();
    
    public function export(string $fileType): void
    {
        $exportRecord = ExportRecord::create([
            'type' => $this->getModelClass(),
            'file_type' => $fileType,
            'status' => 'pending'
        ]);

        $exportClass = $this->getExportClass();
        (new $exportClass($exportRecord->id))->queue($exportRecord->filename);
    }

    public function import(UploadedFile $file): void
    {
        $importClass = $this->getImportClass();
        (new $importClass)->queue($file);
    }

    public function exports()
    {
        return ExportRecord::query()
            ->where('type', $this->getModelClass())
            ->latest()
            ->get();
    }

    public function downloadFile($file): StreamedResponse
    {
        $record = ExportRecord::findOrFail($file);
        
        if (!Storage::exists($record->file_path)) {
            throw AppException::fileNotFound();
        }

        return Storage::download($record->file_path);
    }
}