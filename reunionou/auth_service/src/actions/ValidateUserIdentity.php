<?php

namespace reunionou\auth\actions;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\services\DbService;

final class ValidateUserIdentity extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        if (!isset($rq->getHeaders()['Authorization'])) {
            return (throw new HttpNotAuthorized($rq, "No authorization header present"));
        }

        $id = $args['id'];
        if (!isset($id)) {
            return (throw new HttpInputNotValid($rq, "No id present"));
        }

        $h = $rq->getHeaders()['Authorization'][0];
        $tokenstring = sscanf($h, "Bearer %s")[0];
        $db_service = new DbService($this->container->get('mongo_url'));
        $user = $db_service->getUser($id);
        if ($user == null) {
            return (throw new HttpNotAuthorized($rq, "User does not exist"));
        }

        $token = JWT::decode($tokenstring, new Key($this->container->get('secret'), 'HS512'));
        if ($token->uid != $id) {
            return $rs->withStatus(401);
        } else {
            $rs = $rs->withStatus(200)
                ->withHeader('Content-Type', 'application/json;charset=utf-8');
            return $rs;
        }
    }
}
