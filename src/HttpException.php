<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick-http)
 *
 * @link       https://github.com/milejko/kuick-http
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http;

use InvalidArgumentException;
use RuntimeException;

class HttpException extends RuntimeException
{
    public function __construct(int $code = 500, string $message = 'Internal Server Error')
    {
        $this->validateCode($code);
        parent::__construct($message, $code);
    }

    private function validateCode(int $code): void
    {
        if ($code < 100 || $code > 599) {
            throw new InvalidArgumentException("Invalid HTTP status code: $code");
        }
    }
}
