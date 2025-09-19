<?php

namespace Tests\Kuick\Http\Unit\Mocks;

use Kuick\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AnotherMockHttpMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // always return 500 for POST requests
        if ('POST' === $request->getMethod()) {
            return new Response(500);
        }
        return $handler->handle($request);
    }
}
