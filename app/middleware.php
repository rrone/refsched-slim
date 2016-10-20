<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath(substr($path, 0, -1));
        return $response->withRedirect((string)$uri, 301);
    }

    return $next($request, $response);
});

$c = $app->getContainer();
$logger = $c->get('logger');
$c["jwt"] = function ($c) {
    return new StdClass;
};

$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => getenv("JWT_SECRET"),
    "secure" => false,
    "path" => ["/"],
    "passthrough" => ["/","/logon", "/logon/auth"],
    "attribute" => "jwt",
    "logger" => $logger,
    "callback" => function ($request, $response, $arguments) use ($c) {
        $c['jwt'] = $arguments;
    }
]));
