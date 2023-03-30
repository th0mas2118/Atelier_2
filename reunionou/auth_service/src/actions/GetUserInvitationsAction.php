<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\auth\services\InvitationService;
use reunionou\auth\actions\AbstractAction;
use reunionou\auth\errors\exceptions\HttpNotFound;

final class GetUserInvitationsAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $invitationService = new InvitationService($this->container->get('mongo_event_url'));
        $invitations = $invitationService->getUserInvitations($args['id']);
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();


        if (!isset($invitations)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $invitationList = [];

        foreach ($invitations as $invitation) {
            // $invitation->uri = $routeParser->urlFor('get_invitation', ['id' => strval($invitation["_id"])]);
            $invitation->id = strval($invitation["_id"]);
            unset($invitation["_id"]);
            array_push($invitationList, $invitation);
        }

        $data = [
            'type' => 'collection',
            'count' => count($invitationList),
            'invitations' => $invitationList,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('get_user_invitations', ['id' => $args['id']])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
