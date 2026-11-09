<?php
/* Excepción con código HTTP (404, 403...). */

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class HttpException extends Exception
{
    public function __construct(
        private readonly int $statusCode,
        string $message = '',
    ) {
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
