<?php

namespace App\Models;

use App\Enums\ExportStatus;
use Illuminate\Database\Eloquent\Model;

class ExportRecord extends Model
{
    protected $guarded = false;
    protected $cast=[
        'status'=>ExportStatus::class,
    ];
}
