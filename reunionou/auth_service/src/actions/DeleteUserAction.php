<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\services\DbService;

/**
 * @OA\Delete(
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
 *         response="204",
 *         description="Utilisateur supprimé",
 *     ),
 * )
 */
final class DeleteUserAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $id = $args['id'];
        $db_service = new DbService($this->container->get('mongo_url'));
        $db_service->deleteUser($id);

        $rs->withStatus(204);
        return $rs;
    }
}
