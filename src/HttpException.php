<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-http)
 *
 * @link       https://github.com/milejko/kuick-http
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

use RuntimeException;
use Throwable;

class HttpException extends RuntimeException
{
    public function __construct(
        string $message = 'Internal Server Error',
        int $code = 500,
        ?Throwable $previous = null
    ) {
        $this->validateCode($code);
        parent::__construct($message, $code, $previous);
    }

    private function validateCode(int $code): void
    {
        if ($code < 100 || $code > 599) {
            throw new RuntimeException('Invalid HTTP status code: ' . $code);
        }
    }
}
