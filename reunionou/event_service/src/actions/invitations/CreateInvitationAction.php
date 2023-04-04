<?php

namespace reunionou\event\actions\invitations;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpInputNotValid;
use reunionou\event\services\InvitationService;

/**
 * @OA\Post(
 *     path="/invitations/{id}/",
 *     tags={"Event"},
 *     summary="Créer une invitation pour un utilisateur",
 *     description="Créer une invitation pour un utilisateur",
 *     operationId="createInvitation",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de l'événement",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="5f9b9b9b9b9b9b9b9b9b9b9b"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Informations de l'invitation",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
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
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Invitation créée",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="type",
 *                 type="string",
 *                 example="resource"
 *             ),
 *             @OA\Property(
 *                 property="invitation",
 *                 type="object",
 *                 @OA\Property(
 *                     property="link",
 *                     type="string",
 *                     example="http://localhost:8080/invitations/5f9b9b9b9b9b9b9b9b9b9b9b"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Données invalides",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="type",
 *                 type="string",
 *                 example="error"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Données invalides"
 *             ),
 *             @OA\Property(
 *                 property="code",
 *                 type="integer",
 *                 example=400
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Événement non trouvé",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="type",
 *                 type="string",
 *                 example="error"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Événement non trouvé"
 *             ),
 *             @OA\Property(
 *                 property="code",
 *                 type="integer",
 *                 example=404
 *             )
 *         )
 *     )
 * )
 */
final class CreateInvitationAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $invitation = $invitationService->createUserInvitation($args["id"], $body["event_title"], $body["organizre"], $body["user"]);
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $data = [
            'type' => 'resource',
            'invitation' => [
                "link" => $routeParser->urlFor('get_invitation', ['id' => strval($invitation)])
            ],
        ];

        $rs->getBody()->write(json_encode($data));
        $rs->withStatus(200);
        return $rs;
    }
}
