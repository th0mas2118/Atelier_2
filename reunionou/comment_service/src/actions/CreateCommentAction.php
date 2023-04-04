<?php

namespace reunionou\comment\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\comment\actions\AbstractAction;
use reunionou\comment\service\CommentService;
use reunionou\comment\errors\exceptions\HttpInputNotValid;

/**
 * @OA\Post(
 *     path="/comment",
 *     tags={"Comment"},
 *     summary="Créer un commentaire",
 *     description="Créer un commentaire",
 *     operationId="createComment",
 *     @OA\RequestBody(
 *         description="Informations du commentaire",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="content",
 *                 type="string",
 *                 example="C'était super !"
 *             ),
 *             @OA\Property(
 *                 property="firstname",
 *                 type="string",
 *                 example="John"
 *             ),
 *             @OA\Property(
 *                 property="lastname",
 *                 type="string",
 *                 example="Doe"
 *             ),
 *             @OA\Property(
 *                 property="event_id",
 *                 type="string",
 *                 example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *             ),
 *             @OA\Property(
 *                 property="member_id",
 *                 type="string",
 *                 example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Commentaire créé",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="type",
 *                 type="string",
 *                 example="resource"
 *             ),
 *             @OA\Property(
 *                 property="comment",
 *                 type="object",
 *             @OA\Property(
 *                 property="content",
 *                 type="string",
 *                 example="C'était super !"
 *             ),
 *             @OA\Property(
 *                 property="firstname",
 *                 type="string",
 *                 example="John"
 *             ),
 *             @OA\Property(
 *                 property="lastname",
 *                 type="string",
 *                 example="Doe"
 *             ),
 *             @OA\Property(
 *                 property="event_id",
 *                 type="string",
 *                 example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *             ),
 *             @OA\Property(
 *                 property="member_id",
 *                 type="string",
 *                 example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *             )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Données envoyées non valides",
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
 *                 example="Données envoyées non valides"
 *             ),
 *             @OA\Property(
 *                 property="code",
 *                 type="integer",
 *                 example="400"
 *             )
 *         )
 *     )
 * )
 */
final class CreateCommentAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        if (
            (!isset($body["content"]) || !v::stringVal()->validate($body["content"])) ||
            (!isset($body["firstname"]) || !v::stringVal()->validate($body["firstname"])) ||
            (!isset($body["lastname"]) || !v::stringVal()->validate($body["lastname"])) ||
            (!isset($body["event_id"]) || !v::stringVal()->validate($body["event_id"])) ||
            (!isset($body["member_id"]) || !v::stringVal()->validate($body["member_id"]))
        ) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }

        $commentService = new CommentService($this->container->get('mongo_url'));
        $comment = $commentService->createComment($body);
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
        print_r($comment);
        $data = [
            'type' => 'resource',
            'event' => $body,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('getComment', ['id' => $comment])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
