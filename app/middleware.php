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

$c = $app->getContainer();
$logger = $c->get('logger');
$c["jwt"] = function ($c) {
    return new StdClass;
};

$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => getenv("JWT_SECRET"),
    "secure" => false,
    "path" => ["/"],
    "passthrough" => ["/","/logon"],
    "attribute" => "jwt",
    "logger" => $logger,
    "callback" => function ($request, $response, $arguments) use ($c) {
        $c['jwt'] = $arguments;
    }
]));

//$app->add(function (Request $request, Response $response, callable $next) use ($app) {
//
//    $c = $app->getContainer();
//    $tm = $c->get('tokenManager');
//    $lg = $c->get('logger');
//
//    $cookie = $tm->getData($request);
//    if(is_object($cookie)) {
//        $user = $cookie->data->user;
//        $lg->info("middleware 1 : request : user: " . $user);
//        $event = $cookie->data->event;
//        $lg->info("middleware 1 : request : event: " . $event->name);
//    }
//
//    $response = $next($request, $response);
//
//    $requestCookie = FigRequestCookies::get($request, 'token');
//    $requestCookie = $requestCookie->getValue();
//
//    $response = FigResponseCookies::set($response, SetCookie::create('token')
//        ->withValue($requestCookie)
//    );
//
//    $responseCookie = FigRequestCookies::get($request, 'token');
//    $responseCookie = $responseCookie->getValue();
//    print_r('middleware 2');var_dump($responseCookie);
//
//    return $response;
//});