<?php

namespace reunionou\frontwebapp\middlewares;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Handlers\Strategies\RequestHandler;

final class ValidateToken
{
    public function __invoke(Request $rq, RequestHandlerInterface $handler): Response
    {
        $client = new Client([
            'base_uri' => 'http://api.auth.reunionou',
            'timeout' => 5.0
        ]);

        $response = $client->request('GET', '/validate', [
            'headers' => [
                'Authorization' => $rq->getHeader('Authorization')[0]
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            return $handler->handle($rq);
        } else {
            $rs = new Response();
            $rs = $rs->withStatus(201)
                ->withHeader('Content-Type', 'application/json;charset=utf-8');

            $rs->getBody()->write(strval($response->getBody()));

            return  $rs;
        }
    }
}
