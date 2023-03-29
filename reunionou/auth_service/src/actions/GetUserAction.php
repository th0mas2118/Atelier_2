<?php

namespace reunionou\auth\actions;

use reunionou\auth\errors\exceptions\HttpNotFound;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\auth\services\DbService;

final class GetUserAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $db_service = new DbService($this->container->get('mongo_url'));
        $user = $db_service->getUser($args['id']);

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        if (!isset($user)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressources demandée ne corrspond à aucune ressource disponile: " . $args['id']));
        }

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $user->id = strval($user['_id']);
        unset($user['_id']);

        //Sécurité
        unset($user['password']);
        unset($user['refresh_token']);
        $data = [
            'type' => 'ressource',
            'user' => $user,
            'links' => [
                'self' => ['href' => $routeParser->urlFor('get_user', ['id' => $user->id])],
                'update' => ['href' => $routeParser->urlFor('update_user', ['id' => $user->id])],
                'delete' => ['href' => $routeParser->urlFor('delete_user', ['id' => $user->id])],
            ],
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
