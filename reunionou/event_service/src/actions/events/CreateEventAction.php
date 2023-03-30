<?php

namespace reunionou\event\actions\events;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\services\InvitationService;
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
            (!isset($body["address"]) || !v::stringVal()->validate($body["address"])) ||
            (!isset($body["gps"]) || !v::arrayVal()->validate($body["gps"])) ||
            (!isset($body["organizer"])) ||
            (!isset($body["isPrivate"]) || !v::boolVal()->validate($body["isPrivate"])) ||
            (!isset($body["participants"]) || !v::arrayVal()->validate($body["participants"]))
        ) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }

        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->createEvent($body);

        if (!isset($event)) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être créée"));
        }

        $invitationService = new InvitationService($this->container->get('mongo_url'));

        foreach ($body["participants"] as $participant) {
            $invitationService->createUserInvitation(strval($event), $body["title"], $body["organizer"], $participant["user"]);
        }

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $body["id"] = $event;

        $rs = $rs->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $data = [
            'type' => 'resource',
            'event' => $body,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('get_event', ['id' => strval($event)])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
