<?php

use App\Models\ExportRecord;
use App\Services\Students\Excel\StudentExport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->exportRecord = ExportRecord::factory()->create();
    $this->export = new StudentExport($this->exportRecord);
});

test('export file with headings', function () {
    $headings = $this->export->headings();
    $expected = [
        'First Name',
        'Last Name',
        'Age',
        'Student Number',
        'Level',
    ];
    expect($headings)->toBe($expected);
});

it('export students with column widths', function () {
    $columnWidths = $this->export->columnWidths();
    $expected = [
        'A' => 15,
        'B' => 15,
        'C' => 10,
        'D' => 15,
        'E' => 15,
    ];
    expect($columnWidths)->toBe($expected);
});

it('export students with styles', function () {
    $sheet = Mockery::mock(Worksheet::class);
    $styles = $this->export->styles($sheet);
    $expected = [
        1 => [
            'font' => ['bold' => true, 'color' => ['rgb' => 'ffffff']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0f0f0f']],
            'alignment' => ['horizontal' => 'center'],
        ],
        'C' => ['alignment' => ['horizontal' => 'center']],
        'D' => ['alignment' => ['horizontal' => 'center']],
        'E' => ['alignment' => ['horizontal' => 'center']],
    ];
    expect($styles)->toBe($expected);
});
