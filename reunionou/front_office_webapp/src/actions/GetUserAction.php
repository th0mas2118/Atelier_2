<?php

namespace reunionou\frontwebapp\actions;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetUserAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $id = $args['id'];
        if ($id == null) {
            throw new \Exception("id is null");
        }
        $client  = new Client(['base_uri' => 'http://api.auth.reunionou'], ['timeout' => 2.0]);

        $response = $client->request('GET', "/user/$id",);

        return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', $response->getHeader('Content-Type'))->withBody($response->getBody());
    }
}