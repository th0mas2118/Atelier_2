<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpInputNotValid;

final class RemoveFriend extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $id = $args['id'];
        $friend_id = $args['friend_id'];

        if (
            (!isset($friend_id) ||
                !v::stringVal()->validate($friend_id)) ||
            (!isset($id) || !v::stringVal()->validate($friend_id))
        ) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }

        $db_service = new DbService($this->container->get('mongo_url'));
        $db_service->removeFriend($id, $friend_id);

        $rs->withStatus(204);
        return $rs;
    }
}
