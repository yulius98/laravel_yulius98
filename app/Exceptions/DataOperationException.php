<?php

namespace App\Exceptions;

use Exception;

class DataOperationException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $message = 'Terjadi kesalahan dalam operasi data', int $code = 500)
    {
        parent::__construct($message, $code);
    }
}
