<?php

namespace reunionou\frontwebapp\actions\auth;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use GuzzleHttp\Exception\RequestException;

final class SignUpAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $client  = new Client(['base_uri' => 'http://api.auth.reunionou'], ['timeout' => 2.0]);

        try {
            $response = $client->request('POST', '/signup', ['body' => $rq->getBody()->getContents()]);
        } catch (RequestException $e) {
            $rs->getBody()->write($e->getResponse()->getBody()->getContents());
            return $rs->withStatus($e->getResponse()->getStatusCode())->withHeader('Content-Type', 'application/json');
        }

        return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', $response->getHeader('Content-Type'))->withBody($response->getBody());
    }
}
