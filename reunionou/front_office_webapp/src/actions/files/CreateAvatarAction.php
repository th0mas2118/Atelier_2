<?php

namespace reunionou\frontwebapp\actions\files;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use GuzzleHttp\Exception\ClientException;

final class CreateAvatarAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $id = $args['id'];
        if ($id == null) {
            throw new \Exception("id is null");
        }

        $avatar = $rq->getUploadedFiles()['avatar'];

        try {
            $client  = new Client(['base_uri' => 'http://api.files.reunionou'], ['timeout' => 2.0]);

            $response = $client->request('POST', "/avatars/$id/", [
                'multipart' => [
                    [
                        'name' => 'avatar',
                        'contents' => $avatar->getStream()->getContents(),
                        'filename' => $avatar->getClientFilename(),
                        'headers' => [
                            'Content-Type' => $avatar->getClientMediaType()
                        ]

                    ]
                ]
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', "application/json;charset=utf-8")->withBody($response->getBody());
        }

        if (empty($response->getHeader('Content-Type'))) {
            print_r($response);
            return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', "application/json;charset=utf-8")->withBody($response->getBody());
        }

        return $rs->withStatus($response->getStatusCode())->withHeader('Content-Type', $response->getHeader('Content-Type') ?? [])->withBody($response->getBody());
    }
}
