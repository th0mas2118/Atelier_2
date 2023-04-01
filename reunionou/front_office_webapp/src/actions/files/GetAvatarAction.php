<?php

namespace reunionou\frontwebapp\actions\files;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetAvatarAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $id = $args['id'];
        $width = isset($args['width']) ? intval($args['width']) : 'default';
        $height = isset($args['height']) ? intval($args['height']) : 'default';

        if ($id == null) {
            throw new \Exception("id is null");
        }

        try {
            $client  = new Client(['base_uri' => 'http://api.files.reunionou'], ['timeout' => 2.0]);

            $response = $client->request('GET', "/avatars/$id/$width/$height", ['body' => $rq->getBody()->getContents()]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', "application/json;charset=utf-8")->withBody($response->getBody());
        }

        if (empty($response->getHeader('Content-Type'))) {
            return $rs->withStatus($response->getStatusCode())->withBody($response->getBody());
        }


        return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', $response->getHeader('Content-Type') ?? [])->withBody($response->getBody());
    }
}
