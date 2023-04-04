<?php

namespace reunionou\event\actions\invitations;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\services\InvitationService;
use reunionou\event\errors\exceptions\HttpNotFound;

/**
 *   @OA\Get(
 *       path="/events/{id}/invitations",
 *       tags={"Invitation", "Event"},
        summary="Liste des invitations d'un évenement",
        description="Liste des invitations d'un évenement",
 *       @OA\Parameter(
 *           name="id",
 *           in="path",
 *           description="id de l'évenement",
 *           required=true,
 *           @OA\Schema(
 *               type="string",
 *               example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *           )
 *       ),
 *       @OA\Response(
 *           response="200",
 *           description="Liste des invitations",
 *           @OA\JsonContent(
 *               type="object",
 *               @OA\Property(
 *                   property="type",
 *                   type="string",
 *                   example="collection"
 *               ),
 *               @OA\Property(
 *                   property="count",
 *                   type="integer",
 *                   example=1
 *               ),
 *               @OA\Property(
 *                   property="invitations",
 *                   type="array",
 *                   @OA\Items(
 *                       type="object",
 *                       @OA\Property(
 *                           property="id",
 *                           type="string",
 *                           example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *                       ),
 *                       @OA\Property(
 *                           property="event_title",
 *                           type="string",
 *                           example="Titre de l'évenement"
 *                       ),
 *                       @OA\Property(
 *                           property="event_id",
 *                           type="string",
 *                           example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *                       ),
 *@OA\Property(
 *                 property="user",
 *                 type="object",
 *                 @OA\Property(
 *                     property="firstname",
 *                     type="string",
 *                     example="John"
 *                 ),
 *                 @OA\Property(
 *                     property="lastname",
 *                     type="string",
 *                     example="Doe"
 *                 ),
 *                 @OA\Property(
 *                     property="mail",
 *                     type="string",
 *                     example=""
 *                ),
 *                 @OA\Property(
 *                     property="username",
 *                     type="string",
 *                     example="johndoe"
 *                 )
 *             ),
 *                       @OA\Property(
 *                           property="status",
 *                           type="string",
 *                           example="pending"
 *                       ),
 *                       @OA\Property(
 *                           property="uri",
 *                           type="string",
 *                           example="http://localhost:8080/invitations/5f9f1b9b9b9b9b9b9b9b9b9b"
 *                       )
 *                   )
 *               ),
 *               @OA\Property(
 *                   property="links",
 *                   type="object",
 *                   @OA\Property(
 *                       property="self",
 *                       type="object",
 *                       @OA\Property(
 *                           property="href",
 *                           type="string",
 *                           example="http://localhost:8080/events/5f9f1b9b9b9b9b9b9b9b9b9b/invitations"
 *                       )
 *                   )
 *               )
 *           )
 *       ),
 *       @OA\Response(
 *           response="404",
 *           description="L'identifiant de la ressource demandée ne correspond à aucune ressource disponible",
 *           @OA\JsonContent(
 *               type="object",
 *               @OA\Property(
 *                   property="type",
 *                   type="string",
 *                   example="error"
 *               ),
 *               @OA\Property(
 *                   property="message",
 *                   type="string",
 *                   example="L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: 5f9f1b9b9b9b9b9b9b9b9b9b"
 *               )
 *           )
 *       )
 *   )
 */
final class GetEventInvitationsAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $invitations = $invitationService->getEventInvitations($args['id']);
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();


        if (!isset($invitations)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $invitationList = [];

        foreach ($invitations as $invitation) {
            $invitation->uri = $routeParser->urlFor('get_invitation', ['id' => strval($invitation["_id"])]);
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
                    'href' => $routeParser->urlFor('get_event_invitations', ['id' => $args['id']])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
