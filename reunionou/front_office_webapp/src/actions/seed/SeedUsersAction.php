<?php

namespace reunionou\frontwebapp\actions\seed;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class SeedUsersAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $client  = new Client(['base_uri' => 'http://api.seed.reunionou'], ['timeout' => 2.0]);
        $url = "/users/seed" . ($rq->getQueryParams()['count'] ? "?count=" . $rq->getQueryParams()['count'] : "");

        $response = $client->request('POST', $url, ['body' => $rq->getBody()->getContents()]);

        return $rs->withStatus($response->getStatusCode());
    }
}
