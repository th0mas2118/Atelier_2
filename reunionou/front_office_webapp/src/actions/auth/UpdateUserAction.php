<?php

namespace reunionou\frontwebapp\actions\auth;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class UpdateUserAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $client  = new Client(['base_uri' => 'http://api.auth.reunionou'], ['timeout' => 2.0]);

        $id = $args['id'];

        $url = "/user/" . $id;

        $response = $client->request('PUT', $url, ['body' => $rq->getBody()->getContents()]);

        return $rs->withStatus($response->getStatusCode());
    }
}
