<?php

namespace App\Services\Export;

use App\Contracts\ExportStrategy;
use App\Services\Students\Pdf\StudentPdfExport;
use Exception;

class PdfExportStrategy implements ExportStrategy
{
    public function __construct(private StudentPdfExport $pdfExport) {}

    public function export(): array
    {
        try {
            return $this->pdfExport->generate();
        } catch (Exception $e) {
            return ['error' => 'PDF export failed. please try again...'];
        }
    }
}
