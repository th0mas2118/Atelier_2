<?php

namespace reunionou\event\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpNotFound;
use Slim\Exception\HttpInternalServerErrorException;

final class UpdateEventAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->updateEvent($args['id'], $body);

        if (!isset($event)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        if (!$event) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être modifiée: " . $args['id']));
        }

        $rs = $rs->withStatus(204);

        return $rs;
    }
}
