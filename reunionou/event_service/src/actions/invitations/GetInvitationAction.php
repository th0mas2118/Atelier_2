<?php

namespace reunionou\event\actions\invitations;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpNotFound;
use reunionou\event\services\InvitationService;

/**
 *   @OA\Get(
 *       path="/invitations/{id}",
 *       tags={"Invitation"},
 *       summary="Récupère une invitation",
 *       description="Récupère une invitation",
 *       @OA\Parameter(
 *           name="id",
 *           in="path",
 *           description="id de l'invitation",
 *           required=true,
 *           @OA\Schema(
 *               type="string",
 *               example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *           )
 *       ),
 *       @OA\Response(
 *           response="200",
 *           description="Invitation",
 *           @OA\JsonContent(
 *               type="object",
 *               @OA\Property(
 *                   property="type",
 *                   type="string",
 *                   example="resource"
 *               ),
 *               @OA\Property(
 *                   property="invitation",
 *                   type="object",
 *@OA\Property(
 *                 property="event_title",
 *                 type="string",
 *                 example="Anniversaire de John"
 *             ),
 *             @OA\Property(
 *                 property="organizer",
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
 *                     property="email",
 *                     type="string",
 *                     example="example@example.com"
 *                 ),
 *                 @OA\Property(
 *                     property="username",
 *                     type="string",
 *                     example="johndoe"
 *                 )
 *             ),
 *             @OA\Property(
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
 *                 ),
 *                 @OA\Property(
 *                     property="username",
 *                     type="string",
 *                     example="johndoe"
 *                 )
 *             ),
 *             @OA\Property(
 *               property="status",
 *               type="string",
 *               example="pending"
 *               ),
 *               @OA\Property(
 *                 property="id",
 *                 type="string",
 *                 example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *                 ),
 *                 @OA\Property(
 *                   property="links",
 *                   type="object",
 *                   @OA\Property(
 *                     property="self",
 *                     type="object",
 *                     @OA\Property(
 *                       property="href",
 *                       type="string",
 *                       example="http://localhost:8080/invitations/5f9f1b9b9b9b9b9b9b9b9b9b"
 *                       )
 *                       )
 *                       )
 *                       )
 *                       )
 *                       ),
 *                       @OA\Response(
 *                         response="404",
 *                         description="L'identifiant de la ressource demandée ne correspond à aucune ressource disponible"
 *                         )
 *                         )
 *                         
 */
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
