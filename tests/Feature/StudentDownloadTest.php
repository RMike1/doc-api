<?php

use App\Enums\ExportStatus;
use App\Models\ExportRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('downloads the exported student file if it exists', function () {
    Storage::fake('local');
    $filePath = 'exports/students_2024993'.'xlsx';
    Storage::disk('local')->put($filePath, 'contents..');

    $record = ExportRecord::factory()->create([
        'file_path' => $filePath,
        'status' => ExportStatus::SUCCESS,
    ]);

    $this->getJson(route('download', $record->id))
        ->assertOk()
        ->assertHeader('content-disposition', 'attachment; filename='.basename($filePath));
});
