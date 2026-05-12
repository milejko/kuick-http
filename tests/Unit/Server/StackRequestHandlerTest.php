<?php

namespace Tests\Kuick\Unit\Http;

use Kuick\Http\Server\JsonNotFoundRequestHandler;
use Kuick\Http\Server\StackRequestHandler;
use Tests\Kuick\Http\Unit\Mocks\AnotherMockHttpMiddleware;
use Tests\Kuick\Http\Unit\Mocks\MockHttpMiddleware;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StackRequestHandler::class)]
class StackRequestHandlerTest extends TestCase
{
    public function testIfHandlerProperlyPassesHandlingToTheExceptionHandler(): void
    {
        $handler = new StackRequestHandler(new JsonNotFoundRequestHandler());
        $response = $handler->handle(new ServerRequest('GET', '/something'));
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('{"error":"Not Found"}', $response->getBody()->getContents());
    }

    public function testIfHandlerProperlyPassesHandlingToTheMiddleware(): void
    {
        $handler = new StackRequestHandler(new JsonNotFoundRequestHandler());
        $handler->addMiddleware(new MockHttpMiddleware());
        $response = $handler->handle(new ServerRequest('GET', '/something', [], 'Hello world'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello world', $response->getBody()->getContents());
    }

    public function testIfHandlerPriorityIsMaintained(): void
    {
        $handler = new StackRequestHandler(new JsonNotFoundRequestHandler());
        $handler->addMiddleware(new MockHttpMiddleware());
        $handler->addMiddleware(new AnotherMockHttpMiddleware(), MockHttpMiddleware::class);
        $response = $handler->handle(new ServerRequest('POST', '/something', [], 'Hello world'));
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testIfInexistentMiddlewareUsedAsBeforeClassThrowsException(): void
    {
        $handler = new StackRequestHandler(new JsonNotFoundRequestHandler());
        $this->expectException(\InvalidArgumentException::class);
        $handler->addMiddleware(new MockHttpMiddleware(), 'SomeInexistentMiddlewareClass');
    }
}
