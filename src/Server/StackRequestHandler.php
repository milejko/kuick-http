<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Stack Request Handler implementing the PSR-15 RequestHandlerInterface.
 */
class StackRequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [];

    public function __construct(private RequestHandlerInterface $fallbackHandler)
    {
    }

    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Last middleware in the queue has called on the request handler.
        if (empty($this->middlewares)) {
            return $this->fallbackHandler->handle($request);
        }
        $middleware = array_shift($this->middlewares);
        return $middleware->process($request, $this);
    }
}
