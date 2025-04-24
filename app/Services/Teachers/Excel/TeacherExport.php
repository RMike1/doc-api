<?php

namespace App\Services\Teachers\Excel;

use App\Services\Base\AbstractExport;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;

class TeacherExport extends AbstractExport
{
    protected function getQuery(): Builder
    {
        return Teacher::query()->select(
            'first_name',
            'last_name',
            'email',
            'subject',
            'department'
        );
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Subject',
            'Department',
        ];
    }
}
