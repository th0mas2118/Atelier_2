<?php

namespace reunionou\event\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpNotFound;

final class GetEventAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->getEvent($args['id']);

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();


        if (!isset($event)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $data = [
            'type' => 'resource',
            'order' => $event,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('get_event', ['id' => strval($event["_id"])])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
