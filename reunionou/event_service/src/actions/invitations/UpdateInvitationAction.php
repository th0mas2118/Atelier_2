<?php

namespace reunionou\event\actions\invitations;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\event\actions\AbstractAction;
use reunionou\event\errors\exceptions\HttpInputNotValid;
use reunionou\event\services\InvitationService;

/**
 * @OA\Patch(
 *     path="/invitations/{id}/",
 *     tags={"Event"},
 *     summary="Modifier une invitation pour un utilisateur",
 *     description="Modifier une invitation pour un utilisateur",
 *     operationId="updateInvitation",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de l'événement",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="5f9b9b9b9b9b9b9b9b9b9b9b"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Informations de l'invitation",
 *         required=true
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Invitation modifiée"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Données invalides"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Invitation non trouvée"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur interne"
 *     )
 * )
 */
final class UpdateInvitationAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        $invitationService = new InvitationService($this->container->get('mongo_url'));
        $invitation = $invitationService->updateInvitation($args["id"], $body);

        if (!isset($invitation) || !$invitation) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être modifiée: " . $args['id']));
        }

        $rs = $rs->withStatus(204);
        return $rs;
    }
}
