<?php

namespace App\Services\Export;

use App\Contracts\ExportStrategy;
use App\Services\Students\Pdf\StudentPdfExport;
use Exception;

class PdfExportStrategy implements ExportStrategy
{
    public function __construct(private StudentPdfExport $pdfExport) {}

    public function export(): void
    {
        $result = $this->pdfExport->generate();
        throw_if(! $result, new Exception('PDF export failed. please try again...'));
    }
}
