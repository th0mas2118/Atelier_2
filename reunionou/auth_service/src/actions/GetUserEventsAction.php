<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\auth\services\InvitationService;
use reunionou\auth\actions\AbstractAction;
use reunionou\auth\errors\exceptions\HttpNotFound;
use reunionou\auth\services\EventService;


/**
 * @OA\Get(
 *     path="/user/{id}/events",
 *     tags={"User", "Event"},
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
 *         response="200",
 *         description="Liste des évenements",
 *        @OA\JsonContent(
 *            type="object",
 *           @OA\Property(
 *             property="type",
 *            type="string",
 *           example="collection"
 *          ),
 *          @OA\Property(
 *            property="events",
 *            type="array",
 *            @OA\Items(
 *                type="object",
 *                @OA\Property(
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
 *                    example="2023-04-02T12:24"
 *                ),
 *                @OA\Property(
 *                    property="address",
 *                    type="string",
 *                    example="1 rue de la paix"
 *                ),
 *                @OA\Property(
 *                    property="gps",
 *                    type="array",
 *                    @OA\Items(
 *                        type="number",
 *                        example=1.2345
 *                    ),
 *                    @OA\Items(
 *                        type="number",
 *                        example=1.2345
 *                    )
 *                ),
 *                @OA\Property(
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
 *                 @OA\Property(
 *                   property="participants",
 *                   type="array",
 *                   @OA\Items(
 *                     type="object",
 *                     @OA\Property(
 *                       property="type",
 *                       type="string",
 *                       example="guest | user"
 *                     ),
 *                     @OA\Property(
 *                       property="user",
 *                       type="object",
 *                       @OA\Property(
 *                       property="id",
 *                       type="string",
 *                       example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *                     ),
 *                     @OA\Property(
 *                       property="firstname",
 *                       type="string",
 *                       example="John"
 *                     ),
 *                     @OA\Property(
 *                       property="lastname",
 *                       type="string",
 *                       example="Doe"
 *                     ),
 *                    ),
 *                    @OA\Property(
 *                      property="status",
 *                      type="string",
 *                      example="waiting | accepted | declined"
 *                      ),
 *                      ),
 *                      ),
 *                ),
 *            )
 *        )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="L'identifiant de la ressource demandée ne correspond à aucune ressource disponible",
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
 *                 example="L'identifiant de la ressource demandée ne correspond à aucune ressource disponible"
 *             ),
 *             @OA\Property(
 *                 property="code",
 *                 type="integer",
 *                 example=404
 *             ),
 *           )
 *       )
 * )
    

 */
final class GetUserEventsAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $eventService = new EventService($this->container->get('mongo_event_url'));
        $events = $eventService->getUserEvents($args['id']);
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();


        if (!isset($events)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $eventList = [];

        foreach ($events as $event) {
            // $event->uri = $routeParser->urlFor('get_event', ['id' => strval($event["_id"])]);
            $event->id = strval($event["_id"]);
            unset($event["_id"]);
            array_push($eventList, $event);
        }

        $data = [
            'type' => 'collection',
            'count' => count($eventList),
            'events' => $eventList,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('get_user_events', ['id' => $args['id']])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
