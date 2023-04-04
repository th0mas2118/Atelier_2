<?php

namespace reunionou\auth\actions;

use reunionou\auth\errors\exceptions\HttpNotFound;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\auth\services\DbService;

/**
 * @OA\Get(
 *     path="/user/{id}",
 *     tags={"User"},
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
 *         description="Utilisateur trouvé",
 *        @OA\JsonContent(
 *            type="object",
 *           @OA\Property(
 *             property="type",
 *            type="string",
 *           example="ressource"
 *          ),
 *          @OA\Property(
 *            property="user",
 *            type="object",
 *        @OA\Property(
 *         property="id",
 *       type="string",
 *    example="5f9f1b9b9b9b9b9b9b9b9b9b"
 * ),
 * @OA\Property(
 * property="firstname",
 * type="string",
 * example="John"
 * ),
 * @OA\Property(
 * property="lastname",
 * type="string",
 * example="Doe"
 * ),
 * @OA\Property(
 * property="mail",
 * type="string",
 * example="example@example.com"
 * ),
 * @OA\Property(
 * property="username",
 * type="string",
 * example="johndoe"
 * ),
 * @OA\Property(
 * property="level",
 * type="integer",
 * example=1
 * ),
 * @OA\Property(
 * property="adresse",
 * type="string",
 * example="1 rue de la paix"
 * ),
 *     ),
 * )
 * ),
 * @OA\Response(
 *      response="404",
 *      description="Utilisateur non trouvé",
 *      @OA\JsonContent(
 *        type="object",
 *        @OA\Property(
 *          property="type",
 *          type="string",
 *          example="error"
 *          ),
 *          @OA\Property(
 *            property="message",
 *              type="string",
 *                  example="L'identifiant de la ressources demandée ne corrspond à aucune ressource disponile: 5f9f1b9b9b9b9b9b9b9b9b9b"
 *                  ),
 *                 @OA\Property(
 *                   property="code",
 *                   type="integer",
 *                   example="404",
 *                 )
 *                  ),
 *                  ),
 *                  )   
 *                      
 */
final class GetUserAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $db_service = new DbService($this->container->get('mongo_url'));
        $user = $db_service->getUser($args['id']);

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        if (!isset($user)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressources demandée ne corrspond à aucune ressource disponile: " . $args['id']));
        }

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $user->id = strval($user['_id']);
        unset($user['_id']);

        //Sécurité
        unset($user['password']);
        unset($user['refresh_token']);
        unset($user['friends']);
        $data = [
            'type' => 'ressource',
            'user' => $user,
            'links' => [
                'self' => ['href' => $routeParser->urlFor('get_user', ['id' => $user->id])],
                'update' => ['href' => $routeParser->urlFor('update_user', ['id' => $user->id])],
                'delete' => ['href' => $routeParser->urlFor('delete_user', ['id' => $user->id])],
                'friends' => ['href' => $routeParser->urlFor('get_friends_list', ['id' => $user->id])],
            ],
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
