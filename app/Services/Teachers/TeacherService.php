<?php

namespace App\Services\Teachers;

use App\Models\Teacher;
use App\Services\Base\BaseService;
use App\Services\Teachers\Excel\TeacherExport;
use App\Services\Teachers\Excel\TeacherImport;

class TeacherService extends BaseService
{
    protected function getExportClass()
    {
        return TeacherExport::class;
    }

    protected function getImportClass()
    {
        return TeacherImport::class;
    }

    protected function getModelClass()
    {
        return Teacher::class;
    }
}