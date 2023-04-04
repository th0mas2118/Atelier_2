<?php

namespace reunionou\auth\actions;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Firebase\JWT\ExpiredException;

use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpInputNotValid;

/**
 * @OA\Post(
 *   tags={"Authentification"},
 *   path="/signout",
 *   @OA\Parameter(
 *     name="Authorization",
 *     in="header",
 *     required=true,
 *     @OA\Schema(
 *       type="string",
 *       example="Bearer access_token"
 *       )
 *       ),
 *       @OA\Response(
 *         response="200",
 *         description="Utilisateur déconnecté",
 *       ),
 *       @OA\Response(
 *         response="400",
 *         description="Les données envoyées ne sont pas valides",
 *         @OA\JsonContent(
 *           type="object",
 *           @OA\Property(
 *             property="type",
 *             type="string",
 *             example="error"
 *             ),
 *            @OA\Property(
 *              property="message",
 *              type="string",
 *              example="No authorization header present"
 *              )
 *              )
 *              ),
 *              @OA\Response(
 *                response="401",
 *                description="L'utilisateur n'est pas connecté",
 *              ),
 *              
 * )
 */
final class SignOutAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $headerAuth = $req->getHeaders();
        if (!isset($headerAuth['Authorization'])) {
            return (throw new HttpInputNotValid($req, "No authorization header present"));
        }
        $headerAuth = $headerAuth['Authorization'][0];
        $userAuth = sscanf($headerAuth, "Bearer %s")[0];
        $db_service = new DbService($this->container->get('mongo_url'));
        $db_service->signOut($userAuth);

        $rs->withStatus(200);
        return $rs;
    }
}
