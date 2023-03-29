<?php

namespace reunionou\frontwebapp\actions\auth;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class DeleteFriendAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $client  = new Client(['base_uri' => 'http://api.auth.reunionou'], ['timeout' => 2.0]);

        $id = $args['id'];
        $friend_id = $args['friend_id'];
        if ($id == null || $friend_id == null) {
            throw new \Exception("id is null");
        }

        $url = "/user/" . $id . "/friends/" . $friend_id;

        $response = $client->request('DELETE', $url);

        return $rs->withStatus($response->getStatusCode());
    }
}
