<?php

namespace reunionou\event\actions\events;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpNotFound;

final class GetEventsAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $eventService = new EventService($this->container->get('mongo_url'));
        $events = $eventService->getEvents();
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $eventList = [];

        foreach ($events as $event) {
            $event->uri = $routeParser->urlFor('get_event', ['id' => strval($event["_id"])]);
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
                    'href' => $routeParser->urlFor('get_events')
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
