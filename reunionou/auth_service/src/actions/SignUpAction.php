<?php

namespace reunionou\auth\actions;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpNotAuthorized;


final class SignUpAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        if (null === $rq->getParsedBody()) {
            $body = json_decode($rq->getBody()->getContents(), true);
        } else {
            $body = $rq->getParsedBody();
        }

        $db_service = new DbService($this->container->get('mongo_url'));
        $user = $db_service->signUp($body);


        $rs = $rs->withStatus(201)
            ->withHeader('Content-Type', 'application/json;charset=utf-8');

        $rs->getBody()->write(json_encode($body));
        return $rs;
    }
}
