<?php

namespace reunionou\event\actions\events;

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
 *     path="/events",
 *     tags={"Event"},
 *     summary="Créer un événement",
 *     description="Créer un événement",
 *     operationId="createEvent",
 *     @OA\RequestBody(
 *         description="Informations de l'événement",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 example="Anniversaire de John"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 example="C'est l'anniversaire de John, on va faire la fête !"
 *             ),
 *             @OA\Property(
 *                 property="date",
 *                 type="string",
 *                 example="2020-10-31T12:00:00+02:00"
 *             ),
 *             @OA\Property(
 *                 property="address",
 *                 type="string",
 *                 example="1 rue de la Réunion"
 *             ),
 *             @OA\Property(
 *                 property="gps",
 *                 type="array",
 *                 @OA\Items(
 *                     type="number",
 *                     example="48.123456"
 *                 )
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
 *                 property="isPrivate",
 *                 type="boolean",
 *                 example="false"
 *             ),
 *             @OA\Property(
 *                 property="participants",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(
 *                         property="user",
 *                         type="object",
 *                         @OA\Property(
 *                             property="firstname",
 *                             type="string",
 *                             example="Jane"
 *                         ),
 *                         @OA\Property(
 *                             property="lastname",
 *                             type="string",
 *                             example="Doe"
 *                         ),
 *                         @OA\Property(
 *                             property="id",
 *                             type="string",
 *                             example="5f9b9b9b9b9b9b9b9b9b9b9b"
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="L'événement a été créé",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="type",
 *                 type="string",
 *                 example="resource"
 *             ),
 *             @OA\Property(
 *                 property="event",
 *                 type="object (comme dans le body de la requête)",
 *                 @OA\Property(
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="links",
 *                 type="object",
 *                 @OA\Property(
 *                     property="self",
 *                     type="string",
 *                     example="/event/5f9b9b9b9b9b9b9b9b9b9b9b"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Les données envoyées ne sont pas valides",
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
 *                 example="Les données envoyées ne sont pas valides"
 *             ),
 *             @OA\Property(
 *                 property="code",
 *                 type="integer",
 *                 example=400
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="La ressource demandée n'a pas pu être créée",
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
 *                 example="La ressource demandée n'a pas pu être créée"
 *             ),
 *             @OA\Property(
 *                 property="code",
 *                 type="integer",
 *                 example=500
 *             )
 *         )
 *     )
 * )
 */
final class CreateEventAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        if (
            (!isset($body["title"]) || !v::stringVal()->validate($body["title"])) ||
            (!isset($body["description"]) || !v::stringVal()->validate($body["description"])) ||
            (!isset($body["date"]) || !v::dateTime()->validate($body["date"])) ||
            (!isset($body["address"]) || !v::stringVal()->validate($body["address"])) ||
            (!isset($body["gps"]) || !v::arrayVal()->validate($body["gps"])) ||
            (!isset($body["organizer"])) ||
            (!isset($body["isPrivate"]) || !v::boolVal()->validate($body["isPrivate"])) ||
            (!isset($body["participants"]) || !v::arrayVal()->validate($body["participants"]))
        ) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }

        $eventService = new EventService($this->container->get('mongo_url'));
        $event = $eventService->createEvent($body);

        if (!isset($event)) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être créée"));
        }

        $invitationService = new InvitationService($this->container->get('mongo_url'));

        foreach ($body["participants"] as $participant) {
            $invitationService->createUserInvitation(strval($event), $body["title"], $body["organizer"], $participant["user"]);
        }

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $body["id"] = $event;

        $rs = $rs->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $data = [
            'type' => 'resource',
            'event' => $body,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('get_event', ['id' => strval($event)])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
