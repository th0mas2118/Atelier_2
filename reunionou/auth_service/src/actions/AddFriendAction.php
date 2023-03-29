<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\services\DbService;
use Slim\Routing\RouteContext;
use reunionou\auth\errors\exceptions\HttpInputNotValid;
use Respect\Validation\Validator as v;

final class AddFriendAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $id = $args['id'];
        $body = json_decode($req->getBody()->getContents(), true);

        if (
            (!isset($body['friend_id'])) || !v::stringVal()->validate($body['friend_id']) ||
            (!isset($id) || !v::stringVal()->validate($id))
        ) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }

        $db_service = new DbService($this->container->get('mongo_url'));
        $db_service->addFriend($id, $body['friend_id']);

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(204);
        return $rs;
    }
}
