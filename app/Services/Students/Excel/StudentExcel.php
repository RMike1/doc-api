<?php

namespace App\Services\Students\Excel;

use App\Models\Student;
use App\Exports\StudentExport;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentExcel implements
    FromQuery,
    WithHeadings,
    WithColumnWidths,
    ShouldAutoSize,
    WithStyles,
    ToModel,
    WithStartRow,
    WithValidation,
    WithChunkReading,
    ShouldQueue
{
    use Exportable;

    public function query()
    {
        return Student::select('first_name', 'last_name', 'age', 'student_no', 'level');
    }
    
    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Age',
            'Student Number',
            'Level'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 10,
            'D' => 15,
            'E' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1=> [
                'font' => ['bold' => true, 'color' => ['rgb' => 'ffffff']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0f0f0f']],
                'alignment' => ['horizontal' => 'center']
            ],
            'C'  => ['alignment' => ['horizontal' => 'center']],
            'D'  => ['alignment' => ['horizontal' => 'center']],
            'E'  => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    public function failed(Throwable $exception): void
    {
        
        // Log::error($exception->getMessage());

    }

    //----------------import------------------

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

    function failures()
    {
        return $this->failures();
    }


}
