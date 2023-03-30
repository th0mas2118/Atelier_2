<?php

namespace reunionou\frontwebapp\actions\events;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class UpdateParticipationAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $id = $args['event_id'];
        if ($id == null) {
            throw new \Exception("id is null");
        }
        $client  = new Client(['base_uri' => 'http://api.event.reunionou'], ['timeout' => 2.0]);

        $response = $client->request('PATCH', "/events/$id/participate", ['body' => $rq->getBody()->getContents()]);

        if (empty($response->getHeader('Content-Type'))) {
            return $rs->withStatus($response->getStatusCode())->withBody($response->getBody());
        }

        return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', $response->getHeader('Content-Type') ?? [])->withBody($response->getBody());
    }
}
