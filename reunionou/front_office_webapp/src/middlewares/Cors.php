<?php

namespace reunionou\frontwebapp\middlewares;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

final class Cors
{
    public function __invoke(
        Request $rq,
        RequestHandlerInterface $next
    ): Response {
        if (!$rq->hasHeader('Origin'))
            new HttpUnauthorizedException($rq, "missing Origin Header (cors)");
        $response = $next->handle($rq);
        $response = $response
            ->withHeader('Access-Control-Allow-Origin', "*")
            ->withHeader('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,PATCH,OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Max-Age', 3600)
            ->withHeader('Access-Control-Allow-Credentials', 'true');
        return $response;
    }
}
