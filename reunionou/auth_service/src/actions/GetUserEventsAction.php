<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\auth\services\InvitationService;
use reunionou\auth\actions\AbstractAction;
use reunionou\auth\errors\exceptions\HttpNotFound;
use reunionou\auth\services\EventService;

final class GetUserEventsAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $eventService = new EventService($this->container->get('mongo_event_url'));
        $events = $eventService->getUserEvents($args['id']);
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();


        if (!isset($events)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $eventList = [];

        foreach ($events as $event) {
            // $event->uri = $routeParser->urlFor('get_event', ['id' => strval($event["_id"])]);
            $event->id = strval($event["_id"]);
            unset($event["_id"]);
            array_push($eventList, $event);
        }

        $data = [
            'type' => 'collection',
            'count' => count($eventList),
            'events' => $eventList,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('get_user_events', ['id' => $args['id']])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
