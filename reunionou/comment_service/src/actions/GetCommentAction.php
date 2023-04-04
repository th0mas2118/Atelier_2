<?php

namespace reunionou\comment\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\comment\service\CommentService;
use reunionou\comment\actions\AbstractAction;
use reunionou\comment\errors\exceptions\HttpNotFound;

/**
 * @OA\Get(
 *     path="/comment/{id}",
 *     tags={"Comment"},
 *     summary="Récupérer un commentaire",
 *     description="Récupérer un commentaire",
    
 *     operationId="getComment",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Identifiant du commentaire",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Commentaire récupéré",
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
 *             ),
 *             @OA\Property(
 *               property="links",
 *               type="object",
 *               @OA\Property(
 *                 property="self",
 *                 type="object",
 *                 @OA\Property(
 *                   property="href",
 *                   type="string",
 *                   example="http://localhost:8080/comment/5f9f1b9b9b9b9b9b9b9b9b9b"
 *                   )
 *              )
 *           )
 *         )
 *         ),
 *        @OA\Response(
 *         response="404",
 *         description="Commentaire non trouvé",
 *         @OA\JsonContent(
 *           type="object",
 *           @OA\Property(
 *             property="type",
 *             type="string",
 *             example="error"
 *             ),
 *             @OA\Property(
 *               property="message",
 *               type="string",
 *               example="L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: 5f9f1b9b9b9b9b9b9b9b9b9b"
 *               )
 *               )
 *               )
 *           )
 */
final class GetCommentAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {

        $message = new CommentService($this->container->get('mongo_url'));
        $comment = $message->getComment($args['id']);

        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();

        if (!isset($comment)) {
            return (throw new HttpNotFound($request, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $data = [
            'type' => 'resource',
            'comment' => $comment,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('getComment', ['id' => strval($comment["_id"])])
                ],
            ]
        ];





        $response = $response->withHeader('Content-type', 'application/json;charset=utf-8')->withStatus(202);
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
