<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Kuick\Http\Message\Response;
use Psr\Log\LogLevel;

/**
 * PSR-3 Logger Level for Exception Codes
 */
class ExceptionCodeMap
{
    public const LOG_LEVEL = [
        Response::HTTP_NOT_FOUND => LogLevel::NOTICE,
        Response::HTTP_UNAUTHORIZED => LogLevel::NOTICE,
        Response::HTTP_BAD_REQUEST => LogLevel::NOTICE,
        Response::HTTP_METHOD_NOT_ALLOWED => LogLevel::NOTICE,
        Response::HTTP_FORBIDDEN => LogLevel::NOTICE,
        Response::HTTP_CONFLICT => LogLevel::NOTICE,
        Response::HTTP_NOT_IMPLEMENTED => LogLevel::WARNING,
    ];
}
