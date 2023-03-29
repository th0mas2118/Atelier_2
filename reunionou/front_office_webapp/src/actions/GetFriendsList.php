<?php

namespace reunionou\frontwebapp\actions;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetFriendsList
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $client  = new Client(['base_uri' => 'http://api.auth.reunionou'], ['timeout' => 2.0]);
        $id = $args['id'];
        if ($id == null) {
            throw new \Exception("id is null");
        }


        $url = "/user/" . $id . "/friends";

        $response = $client->request('GET', $url);
        return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', $response->getHeader('Content-Type'))->withBody($response->getBody());
    }
}
