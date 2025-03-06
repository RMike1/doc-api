<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StudentImport implements ToModel, WithStartRow, WithValidation, WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
           'first_name'=>$row[0],
            'last_name'=>$row[1],
            'age'=>$row[2],
            'student_no'=>$row[3],
            'level'=>$row[4],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
             '*.0' => 'required|string|max:255',
             '*.1' => 'required|string|max:255',
             '*.2' => 'required|max:255',
             '*.3' => 'required|max:255',
             '*.4' => Rule::in(['one','two','three']),
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

}
