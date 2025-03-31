<?php

namespace App\Services\Students\Excel;

use App\Models\Student;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class StudentImport implements ShouldQueue, SkipsOnError, SkipsOnFailure, ToModel, WithBatchInserts, WithChunkReading, WithStartRow, WithValidation
{
    public function model(array $row)
    {
        return new Student([
            'first_name' => $row[0],
            'last_name' => $row[1],
            'age' => $row[2],
            'student_no' => $row[3],
            'level' => $row[4],
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
            '*.4' => Rule::in(['one', 'two', 'three']),
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            // Log::warning($failure->errors());
        }
    }

    public function onError(Throwable $e)
    {
        // Log::error($e->getMessage());
    }

    public function failed(Throwable $exception)
    {
        // Log::error($exception->getMessage());
    }
}
