<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\services\DbService;
use Respect\Validation\Validator as v;

final class GetFriendsList extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $id = $args['id'];

        if (!isset($id) || !v::stringVal()->validate($id)) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }
        $db_service = new DbService($this->container->get('mongo_url'));
        $friends = $db_service->getFriendsList($id);

        $data = [
            "type" => 'collection',
            "count" => count($friends),
            "friends" => $friends
        ];

        $rs->getBody()->write(json_encode($data));
        $rs->withStatus(200);
        return $rs;
    }
}
