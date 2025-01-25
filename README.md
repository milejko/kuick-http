# Kuick HTTP
[![Latest Version](https://img.shields.io/github/release/milejko/kuick-http.svg?cacheSeconds=3600)](https://github.com/milejko/kuick-http/releases)
[![PHP](https://img.shields.io/badge/PHP-8.2%20|%208.3%20|%208.4-blue?logo=php&cacheSeconds=3600)](https://www.php.net)
[![Total Downloads](https://img.shields.io/packagist/dt/kuick/http.svg?cacheSeconds=3600)](https://packagist.org/packages/kuick/http)
[![GitHub Actions CI](https://github.com/milejko/kuick-http/actions/workflows/ci.yml/badge.svg)](https://github.com/milejko/kuick-http/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/milejko/kuick-http/graph/badge.svg?token=M3FW3XYJ5J)](https://codecov.io/gh/milejko/kuick-http)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?cacheSeconds=14400)](LICENSE)

## Kuick PSR-15 implementation of HTTP Server Request Handlers

### Key features
1. PSR-15 (https://www.php-fig.org/psr/psr-15/) Request Handler implementation (Stack with a fallback)
2. PSR-7 Response Emitter
3. PSR-7 Response implementation with JsonResponse extension

### Examples
1. Using RequestHandler
```
<?php

use Kuick\Http\StackRequestHandler;
use Kuick\Http\Server\JsonNotFoundRequestHandler;
use Nyholm\Psr7\ServerRequest;

$request = new ServerRequest('GET', '/something');

// handler needs a fallback handler, using JSON one
$handler = new StackRequestHandler(new JsonNotFoundRequestHandler());
// middlewares
// $handler->addMiddleware($someMiddleware);
// $handler->addMiddleware($anotherMiddleware);
$response = $handler->handle($request);

// 404, the response implements PSR-7 ResponseInterface
echo $response->getStatusCode();

```
2. Emitting PSR-7 response
```
<?php

use Kuick\Http\Message\JsonResponse;
use Kuick\Http\Server\ResponseEmitter;

$emitter = new ResponseEmitter();
$response = new JsonResponse(['message' => 'test']);
$emitter->emitResponse($response);
```