<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpInputNotValid;

final class RefreshTokenAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $id = $args['id'];
        $db_service = new DbService($this->container->get('mongo_url'));
        $token = $req->getHeaders();
        if (!isset($token['Authorization'])) {
            return (throw new HttpInputNotValid($req, "No authorization header present"));
        }
        $token = $token['Authorization'][0];
        //remove bearer
        $token = sscanf($token, "Bearer %s");
        $db_service->refreshToken($id, $token[0], $this->container->get('secret'));

        $rs->withStatus(204);
        return $rs;
    }
}
