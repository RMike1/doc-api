<?php

namespace App\Enums;

enum ExportType: string
{
    case EXCEL = 'excel';
    case PDF = 'pdf';

    public function getExtension(): string
    {
        return match($this) {
            self::EXCEL => 'xlsx',
            self::PDF => 'pdf',
        };
    }
}

