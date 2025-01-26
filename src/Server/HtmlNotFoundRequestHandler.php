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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * HTML not found handler
 */
class HtmlNotFoundRequestHandler implements FallbackRequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            Response::HTTP_NOT_FOUND,
            ['X-Request' => base64_encode($request->getUri()->getPath())],
            '<h1>404 Not found</h1>'
        );
    }

    public function handleError(Throwable $exception): ResponseInterface
    {
        return new Response(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            ['X-Error' => $exception->getMessage()],
            '<h1>500 Internal server error</h1>'
        );
    }
}
