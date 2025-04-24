<?php

namespace App\Services\Base;

use App\Enums\ExportStatus;
use App\Exceptions\AppException;
use App\Models\ExportRecord;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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

abstract class AbstractExport implements FromQuery, ShouldAutoSize, ShouldQueue, WithChunkReading, WithColumnWidths, WithEvents, WithHeadings, WithStyles
{
    use Exportable;

    public function __construct(protected string $logId) {}

    abstract public function headings(): array;

    abstract protected function getQuery(): Builder;

    public function query(): Builder
    {
        return $this->getQuery();
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

    public function styles(Worksheet $sheet): array
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
                $this->updateExportStatus(ExportStatus::SUCCESS);
            },
        ];
    }

    public function failed(Throwable $e): void
    {
        $this->updateExportStatus(ExportStatus::FAILED);
    }

    protected function updateExportStatus(ExportStatus $status): void
    {
        DB::transaction(function () use ($status) {
            $exportRecord = ExportRecord::find($this->logId);
            throw_unless($exportRecord, AppException::recordNotFound());
            $exportRecord->update(['status' => $status]);
        });
    }
}
