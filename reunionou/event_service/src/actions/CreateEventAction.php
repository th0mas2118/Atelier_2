<?php

namespace reunionou\event\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpInputNotValid;


final class CreateEventAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        if (
            (!isset($body["title"]) || !v::stringVal()->validate($body["title"])) ||
            (!isset($body["description"]) || !v::stringVal()->validate($body["description"])) ||
            (!isset($body["date"]) || !v::dateTime()->validate($body["date"])) ||
            (!isset($body["location"]) || !v::arrayVal()->validate($body["location"])) ||
            (!isset($body["organizer_id"]) || !v::stringVal()->validate($body["organizer_id"])) ||
            (!isset($body["participants"]) || !v::arrayVal()->validate($body["participants"]))
        ) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }

        $eventService = new EventService($this->container->get('mongo_url'));
        // $event = $eventService->createEvent(["title" => "salut"]);
        $event = null;
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $data = [
            'type' => 'resource',
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('get_event', ['id' => strval($event["_id"])])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($event));
        return $rs;
    }
}
