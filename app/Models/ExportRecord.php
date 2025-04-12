<?php

namespace App\Models;

use App\Enums\ExportStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class ExportRecord extends Model
{
    use HasUlids;

    protected $cast = [
        'status' => ExportStatus::class,
    ];
}
