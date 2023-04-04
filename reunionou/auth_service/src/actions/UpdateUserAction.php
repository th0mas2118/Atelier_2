<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpInputNotValid;

/**
 * @OA\Put(
 *   path="/users/{id}",
 *   tags={"User"},
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     description="id de l'utilisateur",
 *     required=true,
 *     @OA\Schema(
 *       type="string",
 *       example="5f9f1b9b9b9b9b9b9b9b9b9b"
 *     )
 *   ),
 *   @OA\RequestBody(
 *     description="Données de l'utilisateur (basé sur les données envoyées lors de la création d'un utilisateur)",
 *     required=true,
 *     @OA\JsonContent(
 *       type="object",
 *     )
 *   ),
 *   @OA\Response(
 *     response="204",
 *     description="Utilisateur modifié",
 *   ),
 *   @OA\Response(
 *     response="400",
 *     description="Données envoyées non valides",
 *   ),
 *   @OA\Response(
 *     response="404",
 *     description="Utilisateur non trouvé",
 *   )
 * )
 */
final class UpdateUserAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $id = $args['id'];
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        if (!isset($body['mail']) && !isset($body['adresse']) && !isset($body['firstname']) && !isset($body['lastname'])) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }


        $db_service = new DbService($this->container->get('mongo_url'));
        $db_service->updateUser($id, $body);

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(204);
        return $rs;
    }
}
