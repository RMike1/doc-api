<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class ExportFailedException extends Exception
{
    public $message = 'Export Failed!';

    public $code = 400;

    public function report() {}

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
