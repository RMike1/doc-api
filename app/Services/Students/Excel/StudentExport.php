<?php

namespace App\Services\Students\Excel;

use App\Enums\ExportStatus;
use App\Models\ExportRecord;
use App\Models\Student;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Throwable;

class StudentExport implements FromQuery, ShouldAutoSize, ShouldQueue, WithChunkReading, WithColumnWidths, WithEvents, WithHeadings, WithStyles
{
    use Exportable;

    public function __construct(protected $logId) {}

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
            'Level',
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
                'alignment' => ['horizontal' => 'center'],
            ],
            'C' => ['alignment' => ['horizontal' => 'center']],
            'D' => ['alignment' => ['horizontal' => 'center']],
            'E' => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                DB::transaction(function () {
                    $exportRecord = ExportRecord::find($this->logId);
                    throw_unless($exportRecord, Exception::class, 'Export record not found..');
                    $exportRecord->update(['status' => ExportStatus::SUCCESS]);
                });
            },
        ];
    }

    public function failed(Throwable $e)
    {
        DB::transaction(function () use ($e) {
            $exportRecord = ExportRecord::find($this->logId);
            throw_unless($exportRecord, Exception::class, 'Export record not found..');
            $exportRecord->update([
                'status' => ExportStatus::FAILED,
            ]);
            Log::error('Export failed: '.$e->getMessage());
        });
    }
}
