<?php

/**
 * Kuick Framework (https://github.com/milejko/kuick)
 *
 * @link       https://github.com/milejko/kuick
 * @copyright  Copyright (c) 2010-2025 Mariusz Miłejko (mariusz@milejko.pl)
 * @license    https://en.wikipedia.org/wiki/BSD_licenses New BSD License
 */

namespace Kuick\Http\Server;

use Kuick\Http\Message\JsonResponse;
use Kuick\Http\Message\Response;
use Kuick\Http\Server\ExceptionRequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Exception;

/**
 * Exception Handler (rendering JSON)
 */
class ExceptionJsonRequestHandler implements ExceptionRequestHandlerInterface
{
    private const EXCEPTION_CODE_LOG_LEVEL_MAP = [
        Response::HTTP_NOT_FOUND => LogLevel::NOTICE,
        Response::HTTP_UNAUTHORIZED => LogLevel::NOTICE,
        Response::HTTP_BAD_REQUEST => LogLevel::NOTICE,
        Response::HTTP_METHOD_NOT_ALLOWED => LogLevel::NOTICE,
        Response::HTTP_FORBIDDEN => LogLevel::NOTICE,
        Response::HTTP_CONFLICT => LogLevel::NOTICE,
        Response::HTTP_NOT_IMPLEMENTED => LogLevel::WARNING,
    ];

    private Exception $exception;

    public function __construct(private LoggerInterface $logger)
    {
        $this->exception = new Exception('Internal Server Error');
    }

    public function setException(Exception $exception): self
    {
        $this->exception = $exception;
        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $logLevel = self::EXCEPTION_CODE_LOG_LEVEL_MAP[$this->exception->getCode()] ?? LogLevel::ERROR;
        $this->logger->log(
            $logLevel,
            $this->getResponseCode() >= Response::HTTP_INTERNAL_SERVER_ERROR ?
                $this->getExceptionDetailedInformation($request) :
                $this->getExceptionMessage($request)
        );
        return new JsonResponse(['error' => $this->exception->getMessage()], $this->getResponseCode());
    }

    private function getExceptionMessage(ServerRequestInterface $request): string
    {
        return $this->exception->getMessage() . ' [' . $request->getUri() . ']';
    }

    private function getExceptionDetailedInformation(ServerRequestInterface $request): string
    {
        return $this->getExceptionMessage($request) . ' ' .
            $this->exception->getFile() .
            ' (' . $this->exception->getLine() . ') ' .
            $this->exception->getTraceAsString();
    }

    private function getResponseCode(): int
    {
        if ($this->exception->getCode() < Response::HTTP_BAD_REQUEST || $this->exception->getCode() > Response::HTTP_GATEWAY_TIMEOUT) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return $this->exception->getCode();
    }
}
