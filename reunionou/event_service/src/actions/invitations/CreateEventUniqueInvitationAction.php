<?php

namespace reunionou\event\actions\invitations;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpInputNotValid;
use reunionou\event\services\InvitationService;

final class CreateEventUniqueInvitationAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        // $invitationService = new InvitationService($this->container->get('mongo_url'));
        // $invitation = $invitationService->createInvitation($args["id"]);
        // $routeContext = RouteContext::fromRequest($req);
        // $routeParser = $routeContext->getRouteParser();

        // $rs = $rs->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
        // $data = [
        //     'type' => 'resource',
        //     'invitation' => [
        //         "link" => $routeParser->urlFor('get_invitation', ['id' => strval($invitation)])
        //     ],
        // ];

        // $rs->getBody()->write(json_encode($data));
        $rs->withStatus(200);
        return $rs;
    }
}
