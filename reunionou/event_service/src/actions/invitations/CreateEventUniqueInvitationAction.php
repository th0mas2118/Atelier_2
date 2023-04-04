<?php

namespace reunionou\event\actions\invitations;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\services\InvitationService;
use reunionou\event\errors\exceptions\HttpInputNotValid;

/**
 * @OA\Post(
 *     path="/invitations/{id}/guest",
 *     tags={"Event"},
 *     summary="Créer une invitation unique", 
 *     description="Créer une invitation unique",
 *     operationId="createUniqueInvitation",
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
 *                 property="guest_firstname",
 *                 type="string",
 *                 example="John"
 *             ),
 *             @OA\Property(
 *                 property="guest_lastname",
 *                 type="string",
 *                 example="Doe"
 *             ),
 *             @OA\Property(
 *                 property="event_title",
 *                 type="string",
 *                 example="Anniversaire de John"
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
 *                 property="id",
 *                 type="string",
 *                 example="5f9b9b9b9b9b9b9b9b9b9b9b"
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
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Ressource non trouvée",
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
 *                 example="Ressource non trouvée"
 *             )
 *         )
 *     )
 * )
 */
final class CreateEventUniqueInvitationAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $invitation = $invitationService->createUniqueInvitation($args["id"], $body['guest_firstname'], $body['guest_lastname'], $body['event_title']);
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $eventService = new EventService($this->container->get('mongo_url'));

        $participant = [
            "type" => "guest",
            "status" => "waiting",
            "user" => [
                "id" => strval($invitation),
                "firstname" => $body['guest_firstname'],
                "lastname" => $body['guest_lastname'],
            ]
        ];

        $event = $eventService->addParticipant($args["id"], $participant);

        if (!isset($invitation) || !$event) {
            return (throw new HttpInputNotValid($req, "La ressource demandée n'a pas pu être modifiée: " . $args['id']));
        }

        $rs = $rs->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $data = [
            'type' => 'resource',
            'invitation' => [
                "id" => strval($invitation)
            ],
        ];

        $rs->getBody()->write(json_encode($data));
        $rs->withStatus(201);
        return $rs;
    }
}
