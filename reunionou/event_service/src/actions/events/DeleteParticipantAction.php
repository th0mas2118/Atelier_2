<?php

namespace reunionou\event\actions\events;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\services\InvitationService;
use Slim\Exception\HttpInternalServerErrorException;

/**
 * @OA\Delete(
 *     path="/events/{event_id}/participants",
 *     tags={"Event"},
 *    @OA\Parameter(
 *         name="event_id",
 *         in="path",
 *         description="id de l'événement",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Suppression d'un participant",
 *         required=true,
 *         @OA\JsonContent(
 *            type="object",
 *            @OA\Property(
 *              property="member_id",
 *              type="string",
 *              example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *            ),
 *            @OA\Property(
 *              property="is_guest",
 *              type="boolean",
 *              example="true"
 *            )
 *         )
 *      ),
 *      @OA\Response(
 *        response="204",
 *        description="Participant supprimé"
 *      ),
 *      @OA\Response(
 *        response="500",
 *        description="Erreur lors de la suppression du participant",
 *       @OA\JsonContent(
 *            type="object",
 *            @OA\Property(
 *              property="message",
 *              type="string",
 *              example="La ressource demandée n'a pas pu être supprimée: 5f9f1b9b9b9b9b9b9b9b9b9b"
 *            ),
 *           @OA\Property(
 *              property="type",
 *              type="string",
 *              example="error"
 *            ),
 *           @OA\Property(
 *              property="code",
 *              type="integer",
 *              example="500"
 *            )
 *         )
 *      )
 * )
 */
final class DeleteParticipantAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->deleteParticipant($args["id"], $body["member_id"]);

        $invitationService = new InvitationService($this->container->get('mongo_url'));

        if (isset($body["is_guest"]) && $body["is_guest"] == true) {
            $deletedInvitation = $invitationService->deleteInvitation($body["member_id"]);
        } else {
            $invitationId = $invitationService->getInvitationWithUserAndEvent($args["id"], $body["member_id"]);
            $deletedInvitation = $invitationService->deleteInvitation($invitationId);
        }

        if (!isset($event) || !$event) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être supprimée: " . $args['id']));
        }

        $rs = $rs->withStatus(204)->withHeader('Content-Type', 'application/json;charset=utf-8');
        return $rs;
    }
}
