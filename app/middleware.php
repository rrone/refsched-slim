<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\SetCookie;

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

$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => getenv("JWT_KEY"),
    "secure" => false,
    "path" => "/",
    "passthrough" => ["/", "logon"],
    "attribute" => false
]));

$app->add(function (Request $request, Response $response, callable $next) {

    $requestCookie = FigRequestCookies::get($request, 'token');

//print_r('middleware 1');var_dump($request);

    $response = $next($request, $response);

    $response = FigResponseCookies::set($response, SetCookie::create('token')
        ->withValue($requestCookie->getValue())
    );

//    print_r('middleware 2');var_dump($response);


    return $response;
});