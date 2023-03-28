<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\services\DbService;

final class GetUserAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $id = $args['id'];
        $db_service = new DbService($this->container->get('mongo_url'));
        $user = $db_service->getUser($id);
        $rs = $rs->withStatus(200)
            ->withHeader('Content-Type', 'application/json;charset=utf-8');
        $rs->getBody()->write(json_encode($user));
        return $rs;
    }
}
