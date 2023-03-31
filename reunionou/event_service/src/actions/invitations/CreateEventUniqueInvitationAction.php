<?php

namespace reunionou\event\actions\invitations;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\services\InvitationService;
use reunionou\event\errors\exceptions\HttpInputNotValid;

final class CreateEventUniqueInvitationAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $invitation = $invitationService->createUniqueInvitation($args["id"], $body['guest_firstname'], $body['guest_lastname']);
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $eventService = new EventService($this->container->get('mongo_url'));

        $participant = [
            "type" => "guest",
            "status" => "waiting",
            "user" => [
                "id" => strval($invitation),
                "firstname" => $body['guest_firstname'],
                "lastname" => $body['guest_lastname'],
            ]
        ];

        $event = $eventService->addParticipant($args["id"], $participant);

        if (!isset($invitation) || !$event) {
            return (throw new HttpInputNotValid($req, "La ressource demandée n'a pas pu être modifiée: " . $args['id']));
        }

        $rs = $rs->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $data = [
            'type' => 'resource',
            'invitation' => [
                "id" => strval($invitation)
            ],
        ];

        $rs->getBody()->write(json_encode($data));
        $rs->withStatus(201);
        return $rs;
    }
}
