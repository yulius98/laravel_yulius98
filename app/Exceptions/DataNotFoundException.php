<?php

namespace App\Exceptions;

use Exception;

class DataNotFoundException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $message = 'Data tidak ditemukan', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
