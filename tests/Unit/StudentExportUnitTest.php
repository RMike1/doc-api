<?php

// namespace Tests\Feature;
use App\Models\Student;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Services\Students\Excel\StudentExport;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('export file with headings', function () {
    $export= new StudentExport();
    $headings = $export->headings();
    $expected = [
        'First Name',
        'Last Name',
        'Age',
        'Student Number',
        'Level'
    ];
    expect($headings)->toBe($expected);
});

it('export students with column widths', function () {
    $export = new StudentExport();
    $columnWidths = $export->columnWidths();
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
    $export = new StudentExport();
    $sheet = Mockery::mock(Worksheet::class);
    $styles = $export->styles($sheet);
    $expected = [
        1=> [
            'font' => ['bold' => true, 'color' => ['rgb' => 'ffffff']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0f0f0f']],
            'alignment' => ['horizontal' => 'center']
        ],
        'C'  => ['alignment' => ['horizontal' => 'center']],
        'D'  => ['alignment' => ['horizontal' => 'center']],
        'E'  => ['alignment' => ['horizontal' => 'center']],
    ];
    expect($styles)->toBe($expected);
});