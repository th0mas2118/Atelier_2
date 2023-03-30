<?php

namespace reunionou\event\actions\invitations;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\event\actions\AbstractAction;
use reunionou\event\services\InvitationService;
use reunionou\event\errors\exceptions\HttpNotFound;
use Slim\Exception\HttpInternalServerErrorException;

final class DeleteInvitationAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $invitation = $invitationService->deleteInvitation($args['id']);

        if (!isset($invitation)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        if (!$invitation) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être supprimée: " . $args['id']));
        }

        $rs = $rs->withStatus(204);
        return $rs;
    }
}
