<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\auth\services\InvitationService;
use reunionou\auth\actions\AbstractAction;
use reunionou\auth\errors\exceptions\HttpNotFound;

/**
 * @OA\Get(
 *    path="/user/{id}/invitations",
 *    tags={"User", "Invitation"},
 *    @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="id de l'utilisateur",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *         )
 *     ),
 *     @OA\Response(
 *       response="200",
 *       description="Liste des invitations",
 *       @OA\JsonContent(
 *         type="object",
 *         @OA\Property(
 *           property="type",
 *           type="string",
 *           example="collection"
 *         ),
 *         @OA\Property(
 *           property="count",
 *           type="integer",
 *           example=1
 *           ),
 *           @OA\Property(
 *             property="invitations",
 *             type="array",
 *             @OA\Items(
 *               type="object",
 *               @OA\Property(
 *                 property="id",
 *                 type="string",
 *                 example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *               ),
 *               @OA\Property(
 *                 property="event_title",
 *                 type="string",
 *                 example="Anniversaire de John"
 *               ),
 *               @OA\Property(
 *                 property="event_id",
 *                 type="string",
 *                 example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *               ),
 *               @OA\Property(
 *                 property="user (si l'utilisateur invité est inscrit)",
 *                 type="object",
 *        @OA\Property(
 *         property="id",
 *       type="string",
 *    example="5f9f1b9b9b9b9b9b9b9b9b9b"
 * ),
 * @OA\Property(
 * property="firstname",
 * type="string",
 * example="John"
 * ),
 * @OA\Property(
 * property="lastname",
 * type="string",
 * example="Doe"
 * ),
 * @OA\Property(
 * property="username",
 * type="string",
 * example="johndoe"
 * ),
 * @OA\Property(
 * property="mail",
 * type="string",
 * example="example@example.com"
 * ),
 * @OA\Property(
 * property="adresse",
 * type="string",
 * example="1 rue de la paix"
 * ),
 * 
 *                   
 *               ),
 *               @OA\Property(
 *                 property="accepted",
 *                 type="boolean",
 *                 example="false"
 *                 ),
 *                 @OA\Property(
 *                    property="organizer",
 *                    type="object",
 *                    @OA\Property(
 *                        property="id",
 *                        type="string",
 *                        example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *                    ),
 *                    @OA\Property(
 *                        property="firstname",
 *                        type="string",
 *                        example="John"
 *                    ),
 *                    @OA\Property(
 *                        property="lastname",
 *                        type="string",
 *                        example="Doe"
 *                    ),
 *                    @OA\Property(
 *                        property="email",
 *                        type="string",
 *                        example="test@example.com"
 *                    ),
 *                    @OA\Property(
 *                        property="username",
 *                        type="string",
 *                        example="johndoe"
 *                    ),
 *                   ),
 *                   @OA\Property(
 *                     property="is_guest (si l'utilisateur invité n'est pas inscrit)",
 *                     type="boolean",
 *                     example="true"
 *                ),
 *                @OA\Property(
 *                  property="guest_firstname (si l'utilisateur invité n'est pas inscrit)",
 *                  type="string",
 *                  example="John"
 *                  ),
 *                  @OA\Property(
 *                    property="guest_lastname (si l'utilisateur invité n'est pas inscrit)",
 *                    type="string",
 *                    example="Doe"
 *                    ),
 *                ),
 *                )
 *                ),
 * )
 * )
 */
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
