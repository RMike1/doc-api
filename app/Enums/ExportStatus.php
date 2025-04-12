<?php

namespace App\Enums;

enum ExportStatus: string
{
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case PROCESSING = 'processing';
}
