<?php

namespace reunionou\frontwebapp\middlewares;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;

final class ValidateUserLevel
{
    public function __invoke(Request $rq, RequestHandlerInterface $handler): Response
    {
        $client = new Client([
            'base_uri' => 'http://api.auth.local',
            'timeout' => 5.0
        ]);

        $response = $client->request('GET', '/validate', [
            'headers' => [
                'Authorization' => $rq->getHeader('Authorization')[0]
            ]
        ]);


        if ($response->getStatusCode() == 200) {
            $body = json_decode($response->getBody(), true);
            if ($body['userlevel'] >= 10) {
                return $handler->handle($rq);
            } else {
                $rs = new Response();
                $rs = $rs->withStatus(201)
                    ->withHeader('Content-Type', 'application/json;charset=utf-8');

                $rs->getBody()->write(strval('Vous n\'avez pas les droits pour accéder à cette ressource'));

                return  $rs;
            }
        } else {
            $rs = new Response();
            $rs = $rs->withStatus(201)
                ->withHeader('Content-Type', 'application/json;charset=utf-8');

            $rs->getBody()->write(strval($response->getBody()));

            return  $rs;
        }
    }
}
