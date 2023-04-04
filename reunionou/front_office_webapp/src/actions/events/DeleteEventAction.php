<?php

namespace reunionou\frontwebapp\actions\events;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class DeleteEventAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $id = $args['id'];
        if ($id == null) {
            throw new \Exception("id is null");
        }
        $client  = new Client(['base_uri' => 'http://api.event.reunionou'], ['timeout' => 2.0]);

        try {

            $response = $client->request('DELETE', "/events/$id/", ['body' => $rq->getBody()->getContents()]);
        } catch (RequestException $e) {
            $rs->getBody()->write($e->getResponse()->getBody()->getContents());
            return $rs->withStatus($e->getResponse()->getStatusCode())->withHeader('Content-Type', 'application/json');
        }

        if (empty($response->getHeader('Content-Type'))) {
            return $rs->withStatus($response->getStatusCode())->withBody($response->getBody());
        }

        return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', $response->getHeader('Content-Type') ?? [])->withBody($response->getBody());
    }
}
