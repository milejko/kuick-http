<?php

namespace Tests\Kuick\Unit\Http;

use Exception;
use Kuick\Http\Server\HtmlNotFoundRequestHandler;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers Kuick\Http\Server\HtmlNotFoundRequestHandler
 */
class HtmlNotFoundRequestHandlerTest extends TestCase
{
    public function testIfHandlerProperlyPassesHandlingToTheExceptionHandler(): void
    {
        $request = new ServerRequest('GET', '/something');
        $notFoundHandler = new HtmlNotFoundRequestHandler();
        $response = $notFoundHandler->handle($request);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('<h1>404 Not Found</h1>', $response->getBody()->getContents());
    }

    public function testIfErrorIsGeneratedForTheException(): void
    {
        $notFoundHandler = new HtmlNotFoundRequestHandler();
        $response = $notFoundHandler->handleError(new Exception('Something went wrong'));
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('<body><h1>Internal Server Error</h1></body>', $response->getBody()->getContents());
        $this->assertEquals('Something went wrong', $response->getHeaderLine('X-Error'));
    }
}
