<?php

namespace App\Services\Students\Excel;

use App\Models\Student;
use App\Services\Base\AbstractExport;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;

class StudentExport extends AbstractExport
{
    use Exportable;

    public function getQuery(): Builder
    {
        return Student::query()->select('first_name', 'last_name', 'age', 'student_no', 'level');
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Age',
            'Student Number',
            'Level',
        ];
    }
}
