<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;

class AppException extends Exception
{
    public static function recordNotFound(string $message = 'Record not found', int $code = 404): AppException
    {
        return new self($message, $code);
    }
    public static function fileNotFound(string $message = 'File not found', int $code = 404): AppException
    {
        return new self($message, $code);
    }
    public static function exportFailed(string $message = 'Export failed', int $code = 500): AppException
    {
        return new self($message, $code);
    }
    
    public static function invalidFileType(string $message = 'Invalid file type', int $code = 422): AppException
    {
        return new self($message, $code);
    }
    public static function couldNotFindData(string $message = 'No data to export', int $code = 400): AppException
    {
        return new self($message, $code);
    }
    
    public static function importFailed(string $message = 'Import failed', int $code = 500): AppException
    {
        return new self($message, $code);
    }

}
