<?php

namespace Tests\Kuick\Unit;

use Kuick\Http\HttpException;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

/**
 * @covers Kuick\Http\HttpException
 */
class HtmlExceptionTest extends TestCase
{
    public function testIfBrokenHttpCodeGivesInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid HTTP status code: 999');
        new HttpException(999);
    }

    public function testIfCustomMessageIsPassed(): void
    {
        $exception = new HttpException(404, 'Not Found');
        $this->assertEquals('Not Found', $exception->getMessage());
        $this->assertEquals(404, $exception->getCode());
    }
}
