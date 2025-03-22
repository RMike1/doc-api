<?php

namespace App\Contracts;

interface ExportStrategy
{
    public function export(): array;
}
