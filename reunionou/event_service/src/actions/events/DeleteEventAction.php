<?php

namespace reunionou\event\actions\events;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpNotFound;
use Slim\Exception\HttpInternalServerErrorException;

final class DeleteEventAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->deleteEvent($args['id']);

        if (!isset($event)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        if (!$event) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être supprimée: " . $args['id']));
        }

        $rs = $rs->withStatus(204);
        return $rs;
    }
}
