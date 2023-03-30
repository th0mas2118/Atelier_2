<?php

namespace reunionou\frontwebapp\actions\auth;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetUsersAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $client  = new Client(['base_uri' => 'http://api.auth.reunionou'], ['timeout' => 2.0]);
        $search = $req->getQueryParams();
        if (!isset($search['search']))
            $search = null;
        else
            $search = $search['search'];

        $url = ($search == null) ? '/users' : '/users?search=' . $search;
        echo $url;

        $response = $client->request('GET', $url);

        return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', $response->getHeader('Content-Type'))->withBody($response->getBody());
    }
}
