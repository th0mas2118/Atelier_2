<?php

namespace reunionou\event\actions\events;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Respect\Validation\Validator as v;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\services\InvitationService;
use Slim\Exception\HttpInternalServerErrorException;
use reunionou\event\errors\exceptions\HttpInputNotValid;

/**
 * @OA\Patch(
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
 *         description="Modification de la participation d'un participant",
 *         required=true,
 *         @OA\JsonContent(
 *            type="object",
 *            @OA\Property(
 *              property="user_id",
 *              type="string",
 *              example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *            ),
 *            @OA\Property(
 *              property="status",
 *              type="string",
 *              example="accepted | declined | waiting"
 *            ),
 *            @OA\Property(
 *              property="type",
 *              type="string",
 *              example="user | guest"
 *            )
 *         )
 *      ),
 *      @OA\Response(
 *        response="204",
 *        description="Participant modifié"
 *      ),
 *      @OA\Response(
 *        response="400",
 *        description="Les données envoyées ne sont pas valides"
 *      ),
 *      @OA\Response(
 *        response="404",
 *        description="L'événement n'existe pas"
 *      ),
 *      @OA\Response(
 *        response="500",
 *        description="La ressource demandée n'a pas pu être modifiée"
 *      )
 * )
 */
final class UpdateParticipationAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        if (
            (!isset($body["user_id"]) || !v::stringVal()->validate($body["user_id"])) ||
            (!isset($body["status"]) || !v::stringVal()->validate($body["status"])) ||
            (!isset($body["type"]) || !v::stringVal()->validate($body["type"]))
        ) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }

        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->updateParticipation($args["event_id"], $body["user_id"], $body["status"]);

        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $updatedInvitation = $invitationService->findAndUpdateParticipation($args["event_id"], $body["user_id"], $body["status"], $body["type"]);

        if (!isset($event) || !$event) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être modifiée: " . $args['event_id']));
        }

        $rs = $rs->withStatus(204)->withHeader('Content-Type', 'application/json;charset=utf-8');
        return $rs;
    }
}
