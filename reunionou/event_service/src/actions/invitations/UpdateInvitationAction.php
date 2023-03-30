<?php

namespace reunionou\event\actions\invitations;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpInputNotValid;
use reunionou\event\services\InvitationService;

final class UpdateInvitationAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $invitation = $invitationService->updateInvitation($args["id"], $body);

        if (!isset($invitation) || !$invitation) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être modifiée: " . $args['id']));
        }

        $rs = $rs->withStatus(204);
        return $rs;
    }
}
