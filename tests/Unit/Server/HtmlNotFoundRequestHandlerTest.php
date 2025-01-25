<?php

namespace Tests\Kuick\Unit\Http;

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
        $this->assertEquals('<h1>404 Not found</h1>', $response->getBody()->getContents());
    }
}
