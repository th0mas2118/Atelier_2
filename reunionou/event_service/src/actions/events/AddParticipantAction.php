<?php

namespace reunionou\event\actions\events;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use Slim\Exception\HttpInternalServerErrorException;

final class AddParticipantAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->addParticipant($args["event_id"], $body);

        if (!isset($event) || !$event) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être modifiée: " . $args['event_id']));
        }

        $rs = $rs->withStatus(204)->withHeader('Content-Type', 'application/json;charset=utf-8');
        return $rs;
    }
}
