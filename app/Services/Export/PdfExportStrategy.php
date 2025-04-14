<?php

namespace App\Services\Export;

use Exception;
use App\Exceptions\AppException;
use App\Contracts\ExportStrategy;
use App\Services\Students\Pdf\StudentPdfExport;

class PdfExportStrategy implements ExportStrategy
{
    public function __construct(private StudentPdfExport $pdfExport) {}

    public function export(): void
    {
        $result = $this->pdfExport->generate();
        throw_if(! $result, AppException::exportFailed());
    }
}
