<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use InvalidArgumentException;
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

    public function __construct(private FallbackRequestHandlerInterface $fallbackHandler)
    {
    }

    public function addMiddleware(MiddlewareInterface $middleware, ?string $beforeMiddlewareClassName = null): self
    {
        if (null === $beforeMiddlewareClassName) {
            $this->middlewares[] = $middleware;
            return $this;
        }
        foreach ($this->middlewares as $middlewareIndex => $middlewareInstance) {
            if ($middlewareInstance instanceof $beforeMiddlewareClassName) {
                array_splice($this->middlewares, $middlewareIndex, 0, [$middleware]);
                return $this;
            }
        }
        throw new InvalidArgumentException("Middleware of class {$beforeMiddlewareClassName} not found in the stack.");
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
