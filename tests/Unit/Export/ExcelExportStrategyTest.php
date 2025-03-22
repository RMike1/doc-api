<?php

use App\Services\Export\ExcelExportStrategy;
use App\Services\Students\Excel\StudentExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    $this->strategy = new ExcelExportStrategy();
    Excel::fake();
});

it('stores excel file with correct format n returns success message', function () {
    $now = '20250322095517';
    Carbon::setTestNow(Carbon::createFromFormat('YmdHis', $now));
    
    $result = $this->strategy->export();
    
    Excel::assertQueued("exports/students_{$now}.xlsx");
    expect($result)->toBe(['message' => 'Excel export started!']);
});

it('returns error message when export fails', function () {
    Excel::shouldReceive('store')->andThrow(new \Exception('Export failed'));
    
    $result = $this->strategy->export();
    
    expect($result)->toBe(['error' => 'Excel export failed. please try again...']);
});