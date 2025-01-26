<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Kuick\Http\Message\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * JSON not found handler
 */
class JsonNotFoundRequestHandler implements FallbackRequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(
            ['error' => 'Not Found'],
            JsonResponse::HTTP_NOT_FOUND,
            ['X-Request' => base64_encode($request->getUri()->getPath())]
        );
    }

    public function handleError(Throwable $exception): ResponseInterface
    {
        return new JsonResponse(
            ['error' => 'Internal Server Error'],
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            ['X-Error' => $exception->getMessage()]
        );
    }
}
