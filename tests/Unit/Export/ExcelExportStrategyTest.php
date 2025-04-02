<?php

use App\Services\Export\ExcelExportStrategy;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    $this->strategy = new ExcelExportStrategy;
    Excel::fake();
});

it('stores excel file with correct format n returns success message', function () {
    $now = '20250322095517';
    Carbon::setTestNow(Carbon::createFromFormat('YmdHis', $now));
    $this->strategy->export();
    Excel::assertQueued("exports/students_{$now}.xlsx");
});
