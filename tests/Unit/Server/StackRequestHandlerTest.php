<?php

namespace Tests\Kuick\Unit\Http;

use Kuick\Http\Server\JsonNotFoundRequestHandler;
use Kuick\Http\Server\StackRequestHandler;
use Tests\Kuick\Http\Unit\Mocks\MockHttpMiddleware;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Http\Server\StackRequestHandler
 */
class StackRequestHandlerTest extends TestCase
{
    public function testIfHandlerProperlyPassesHandlingToTheExceptionHandler(): void
    {
        $request = new ServerRequest('GET', '/something');
        $exceptionHandler = new JsonNotFoundRequestHandler();
        $handler = new StackRequestHandler($exceptionHandler);
        $response = $handler->handle($request);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('{"error":"Not found"}', $response->getBody()->getContents());
    }

    public function testIfHandlerProperlyPassesHandlingToTheMiddleware(): void
    {
        $request = new ServerRequest('GET', '/something', [], 'Hello world');
        $exceptionHandler = new JsonNotFoundRequestHandler();
        $handler = new StackRequestHandler($exceptionHandler);
        $handler->addMiddleware(new MockHttpMiddleware());
        $response = $handler->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello world', $response->getBody()->getContents());
    }
}
