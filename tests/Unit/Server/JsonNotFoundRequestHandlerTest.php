<?php

namespace Tests\Kuick\Unit\Http;

use Exception;
use Kuick\Http\HttpException;
use Kuick\Http\Server\JsonNotFoundRequestHandler;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Http\Server\JsonNotFoundRequestHandler
 */
class JsonNotFoundRequestHandlerTest extends TestCase
{
    public function testIfNotFoundResponseIsGenerated(): void
    {
        $request = new ServerRequest('GET', '/something');
        $notFoundHandler = new JsonNotFoundRequestHandler();
        $response = $notFoundHandler->handle($request);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('{"error":"Not Found"}', $response->getBody()->getContents());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
    }

    public function testIfErrorIsGeneratedForTheException(): void
    {
        $notFoundHandler = new JsonNotFoundRequestHandler();
        $response = $notFoundHandler->handleError(new Exception('Something went wrong'));
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('{"error":"Internal Server Error"}', $response->getBody()->getContents());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('Something went wrong', $response->getHeaderLine('X-Error'));
    }

    public function testIfCustomErrorIsGeneratedForTheHttpException(): void
    {
        $notFoundHandler = new JsonNotFoundRequestHandler();
        $response = $notFoundHandler->handleError(new HttpException(502, 'Custom 502'));
        $this->assertEquals(502, $response->getStatusCode());
        $this->assertEquals('{"error":"Custom 502"}', $response->getBody()->getContents());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('Custom 502', $response->getHeaderLine('X-Error'));
    }
}
