<?php

namespace App\Services\Students\Excel;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Log;
use Throwable;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StudentExport implements FromQuery, WithHeadings, WithColumnWidths, ShouldAutoSize, WithStyles, ShouldQueue, WithChunkReading
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
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'ffffff']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0f0f0f']],
                'alignment' => ['horizontal' => 'center']
            ],
            'C'  => ['alignment' => ['horizontal' => 'center']],
            'D'  => ['alignment' => ['horizontal' => 'center']],
            'E'  => ['alignment' => ['horizontal' => 'center']],
        ];
    }


    public function chunkSize(): int
    {
        return 1000; 
    }

    public function failed(Throwable $e)
    {
        // Log::error($e->getMessage());
    }
}