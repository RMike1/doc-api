<?php

use App\Enums\ExportableType;
use App\Enums\ExportType;
use App\Models\Teacher;
use App\Services\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\Snappy\Facades\SnappyPdf;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
    Excel::fake();
    SnappyPdf::fake();
});

describe('export data', function () {
    it('can exports teacher data as excel file', function () {
        Teacher::factory()->count(5)->create();

        $this->getJson(route('export', [
            'file_type' => ExportType::EXCEL->value,
            'school' => ExportableType::TEACHER->value
        ]))->assertStatus(200)
            ->assertJsonStructure(['message']);

        Excel::matchByRegex();
        Excel::assertQueued('/exports\/export_\d{14}_teacher\.xlsx/');
    });

    it('exports teacher data as pdf file', function () {
        Teacher::factory()->count(5)->create();
        
        $exportService = $this->mock(ExportService::class);
        $exportService->shouldReceive('handle')
            ->with(ExportType::PDF, ExportableType::TEACHER)
            ->once();
    
        $this->getJson(route('export', [
            'file_type' => ExportType::PDF->value,
            'school' => ExportableType::TEACHER->value
        ]))->assertOk()
            ->assertJson(['message' => 'Export Started!']);
    });

    it('returns error when invalid export type is selected', function () {
        $this->getJson(route('export', [
            'file_type' => 'invalid',
            'school' => ExportableType::TEACHER->value
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['file_type']);
    });

    it('returns error when invalid exportable type is selected', function () {
        $this->getJson(route('export', [
            'file_type' => ExportType::PDF->value,
            'school' => 'invalid'
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['school']);
    });
});
