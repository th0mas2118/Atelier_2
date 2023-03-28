<?php

namespace reunionou\frontwebapp\actions;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class SignUpAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $client  = new Client(['base_uri' => 'http://api.auth.reunionou'], ['timeout' => 2.0]);

        $response = $client->request('POST', '/signup', ['body' => $rq->getBody()->getContents()]);

        return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', $response->getHeader('Content-Type'))->withBody($response->getBody());
    }
}
