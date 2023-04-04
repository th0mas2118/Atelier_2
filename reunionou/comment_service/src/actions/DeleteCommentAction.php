<?php

namespace reunionou\comment\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\comment\service\CommentService;
use reunionou\comment\actions\AbstractAction;
use reunionou\comment\errors\exceptions\HttpNotFound;

/**
 * @OA\Delete(
 *     path="/comment/{id}",
 *     tags={"Comment"},
 *     summary="Supprimer un commentaire",
 *     description="Supprimer un commentaire",
 *     operationId="deleteComment",
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
 *         description="Commentaire supprimé",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="type",
 *                 type="string",
 *                 example="resource"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Commentaire supprimé avec succès."
 *             ),
 *             @OA\Property(
 *                 property="links",
 *                 type="object",
 *                 @OA\Property(
 *                     property="self",
 *                     type="object",
 *                     @OA\Property(
 *                         property="href",
 *                         type="string",
 *                         example="http://localhost:8080/comment/5f9f1b9b9b9b9b9b9b9b9b9b"
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
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
 *     )
 */
final class DeleteCommentAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {

        $message = new CommentService($this->container->get('mongo_url'));
        $comment = $message->deleteComment($args['id']);

        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();

        if (!isset($comment)) {
            return (throw new HttpNotFound($request, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $message->deleteComment($args['id']);

        $data = [
            'type' => 'resource',
            'message' => 'Commentaire supprimé avec succès.',
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('deleteComment', ['id' => strval($args['id'])])
                ],
            ]
        ];

        $response = $response->withHeader('Content-type', 'application/json;charset=utf-8')->withStatus(202);
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
