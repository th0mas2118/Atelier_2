<?php

namespace reunionou\event\actions\events;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\event\services\EventService;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpNotFound;

/**
 * @OA\Get(
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
 *         response="200",
 *         description="Évenement",
 *        @OA\JsonContent(
 *            type="object",
 *           @OA\Property(
 *             property="type",
 *            type="string",
 *           example="resource"
 *          ),
 *          @OA\Property(
 *            property="event",
 *            type="object",
 *            @OA\Property(
 *                    property="id",
 *                    type="string",
 *                    example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *                ),
 *                @OA\Property(
 *                    property="title",
 *                    type="string",
 *                    example="Anniversaire de John"
 *                ),
 *                @OA\Property(
 *                    property="description",
 *                    type="string",
 *                    example="Anniversaire de John"
 *                ),
 *                @OA\Property(
 *                    property="date",
 *                    type="string",
 *                    example="2020-10-31T00:00:00+00:00"
 *                ),
 *                @OA\Property(
 *                    property="location",
 *                    type="string",
 *                    example="Paris"
 *                ),
 *                @OA\Property(
 *                    property="organizer",
 *                    type="string",
 *                    example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *                ),
 *                @OA\Property(
 *                    property="participants",
 *                    type="array",
 *                    @OA\Items(
 *                    type="object",
 *                    )
 *                ),
 *                @OA\Property(
 *                    property="links",
 *                    type="object",
 *                    @OA\Property(
 *                        property="self",
 *                        type="object",
 *                        @OA\Property(
 *                            property="href",
 *                            type="string",
 *                            example="http://localhost:8080/event/5f9f1b9b9b9b9b9b9b9b9b9b"
 *                        )
 *                    )
 *                )
 *            )
 *        )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Évenement non trouvé",
 *        @OA\JsonContent(
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
 *          @OA\Property(
 *            property="code",
 *            type="integer",
 *            example="404"
 *          )
 *        )
 *     )
 * )
 */
final class GetEventAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->getEvent($args['id']);

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();


        if (!isset($event)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $event->id = strval($event["_id"]);
        unset($event["_id"]);

        $data = [
            'type' => 'resource',
            'event' => $event,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('get_event', ['id' => strval($event["id"])])
                ],
                // 'join' => [
                //     'href' => $routeParser->urlFor('join_event', ['id' => strval($event["id"])])
                // ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
