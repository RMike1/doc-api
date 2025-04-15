<?php

namespace App\Models;

use App\Enums\ExportStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportRecord extends Model
{
    /** @use HasFactory<\Database\Factories\ExportRecordFactory> */
    use HasFactory, HasUlids;

    protected $casts = [
        'status' => ExportStatus::class,
    ];
}
