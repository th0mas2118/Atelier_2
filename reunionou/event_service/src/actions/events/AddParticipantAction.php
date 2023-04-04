<?php

namespace reunionou\event\actions\events;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use Slim\Exception\HttpInternalServerErrorException;

/**
 * @OA\Post(
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
 *         description="Ajout d'un participant",
 *         required=true,
 *         @OA\JsonContent(
 *            type="object",
 *            @OA\Property(
 *              property="type",
 *              type="string",
 *              example="user | guest"
 *            ),
 *            @OA\Property(
 *              property="user",
 *              type="object",
 *              @OA\Property(
 *                property="id",
 *                type="string",
 *                example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *              ),
 *              @OA\Property(
 *                property="firstname",
 *                type="string",
 *                example="John"
 *              ),
 *              @OA\Property(
 *                property="lastname",
 *                type="string",
 *                example="Doe"
 *              )
 *            )
 *         )
 *      ),
 *      @OA\Response(
 *        response="204",
 *        description="Participant ajouté"
 *      ),
 *      @OA\Response(
 *        response="500",
 *        description="Erreur interne du serveur"
 *      )
 * )  
 */
final class AddParticipantAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->addParticipant($args["event_id"], $body);

        if (!isset($event) || !$event) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être modifiée: " . $args['event_id']));
        }

        $rs = $rs->withStatus(204)->withHeader('Content-Type', 'application/json;charset=utf-8');
        return $rs;
    }
}
