<?php

namespace App\Services\Base;

use App\Enums\ExportableType;

abstract class BaseExport
{
    public function __construct(
        protected string $logId,
        protected string $timestamp,
        protected ExportableType $type
    ) {}

    abstract public function export(): string;

    protected function getFilePath(): string
    {
        return "exports/export_{$this->timestamp}_{$this->type->value}.{$this->getExtension()}";
    }

    abstract protected function getExtension(): string;
}