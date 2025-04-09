<?php

namespace App\Exceptions;

use Exception;
use App\Enums\ExportStatus;
use App\Models\ExportRecord;
use Illuminate\Http\Request;

class ExportFailedException extends Exception
{
    public $message = 'Export Failed!';
    public $code = 400;
    // public $logId;

    public function report(){
        
    }

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
