<?php

namespace reunionou\frontwebapp\actions\comment;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class PostCommentEvent
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $client  = new Client(['base_uri' => 'http://api.comment.reunionou'], ['timeout' => 2.0]);

        $response = $client->request('POST', "/messages", ['body' => $rq->getBody()->getContents()]);

        return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', $response->getHeader('Content-Type'))->withBody($response->getBody());
    }
}
