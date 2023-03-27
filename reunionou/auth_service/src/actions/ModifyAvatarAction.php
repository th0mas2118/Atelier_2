<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\services\DbService;

final class ModifyAvatarAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $h = $rq->getHeader('Authorization')[0];
        $acces_token = sscanf($h, "Bearer %s")[0];
        if (null === $rq->getParsedBody()) {
            $body = json_decode($rq->getBody()->getContents(), true);
        } else {
            $body = $rq->getParsedBody();
        }
        $db_service = new DbService($this->container->get('mongo_url'));
        $user = $db_service->modifyAvatar($acces_token, $body);
        $rs = $rs->withStatus(200)
            ->withHeader('Content-Type', 'application/json;charset=utf-8');
        $rs->getBody()->write(json_encode($body));
        return $rs;
    }
}
