<?php

namespace Tests\Kuick\Unit\Http;

use Kuick\Http\Server\JsonNotFoundRequestHandler;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Http\Server\JsonNotFoundRequestHandler
 */
class JsonNotFoundRequestHandlerTest extends TestCase
{
    public function testIfHandlerProperlyPassesHandlingToTheExceptionHandler(): void
    {
        $request = new ServerRequest('GET', '/something');
        $notFoundHandler = new JsonNotFoundRequestHandler();
        $response = $notFoundHandler->handle($request);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('{"error":"Not found"}', $response->getBody()->getContents());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
    }
}
