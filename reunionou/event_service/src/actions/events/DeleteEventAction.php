<?php

namespace reunionou\event\actions\events;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\services\InvitationService;
use reunionou\event\errors\exceptions\HttpNotFound;
use Slim\Exception\HttpInternalServerErrorException;

/**
 * @OA\Delete(
 *     path="/events/{id}",
 *     tags={"Event"},
 *    @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="id de l'évenement",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *         )
 *     ),
 *     @OA\Response(
 *         response="204",
 *         description="Évenement supprimé",
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Évenement non trouvé",
 *         @OA\JsonContent(
 *            type="object",
 *           @OA\Property(
 *             property="type",
 *            type="string",
 *           example="error"
 *          ),
 *          @OA\Property(
 *            property="message",
 *            type="string",
 *            example="L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: 5f9f1b9b9b9b9b9b9b9b9b9b"
 *          ),
 *         ),
 *     ),
 * )
 */
final class DeleteEventAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->deleteEvent($args['id']);

        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $invitationService->deleteEventInvitations($args['id']);

        if (!isset($event)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        if (!$event) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être supprimée: " . $args['id']));
        }

        $rs = $rs->withStatus(204);
        return $rs;
    }
}
