<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvalidExportTypeException extends Exception
{
    public $message = 'Unsupported Export Types';

    public $code = 422;

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        return response()->json([
            $this->message,
        ], $this->code);
    }
}
