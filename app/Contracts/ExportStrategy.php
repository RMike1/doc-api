<?php

namespace App\Contracts;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface ExportStrategy
{
    public function export(): void;

    // public function download(): BinaryFileResponse;
}
