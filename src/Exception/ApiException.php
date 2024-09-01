<?php

namespace App\Exception;

use Doctrine\DBAL\Exception;

class ApiException extends Exception
{
    private int $statusCode;

    public function __construct(string $message = "", int $statusCode = 500, Exception $previous = null)
    {
        parent::__construct(
            $message,
            $statusCode,
            $previous
        );
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}