<?php

namespace reunionou\event\actions\invitations;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpNotFound;
use reunionou\event\services\InvitationService;

final class GetInvitationAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $invitation = $invitationService->getInvitation($args['id']);

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();


        if (!isset($invitation)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $invitation->id = strval($invitation["_id"]);
        unset($invitation["_id"]);

        $data = [
            'type' => 'resource',
            'order' => $invitation,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('get_invitation', ['id' => strval($invitation["id"])])
                ],
                'update' => [
                    'href' => $routeParser->urlFor('update_invitation', ['id' => strval($invitation["id"])])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
