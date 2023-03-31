<?php

namespace reunionou\event\actions\events;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Respect\Validation\Validator as v;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\services\InvitationService;
use Slim\Exception\HttpInternalServerErrorException;
use reunionou\event\errors\exceptions\HttpInputNotValid;

final class UpdateParticipationAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        if (
            (!isset($body["user_id"]) || !v::stringVal()->validate($body["user_id"])) ||
            (!isset($body["status"]) || !v::stringVal()->validate($body["status"])) ||
            (!isset($body["type"]) || !v::stringVal()->validate($body["type"]))
        ) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }

        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->updateParticipation($args["event_id"], $body["user_id"], $body["status"]);

        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $updatedInvitation = $invitationService->findAndUpdateParticipation($args["event_id"], $body["user_id"], $body["status"], $body["type"]);

        if (!isset($event) || !$event) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être modifiée: " . $args['event_id']));
        }

        $rs = $rs->withStatus(204)->withHeader('Content-Type', 'application/json;charset=utf-8');
        return $rs;
    }
}
