<?php

namespace reunionou\files\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\files\services\FileService;
use reunionou\files\actions\AbstractAction;
use reunionou\files\errors\exceptions\HttpInputNotValid;
use Slim\Exception\HttpInternalServerErrorException;

/**
 * @OA\Post(
 *     path="/users/{id}/avatar",
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
 *     @OA\RequestBody(
 *         description="Avatar de l'utilisateur",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(
 *                     property="avatar",
 *                     type="string",
 *                     format="binary"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="Avatar de l'utilisateur",
 *        @OA\JsonContent(
 *            type="object",
 *           @OA\Property(
 *             property="type",
 *            type="string",
 *           example="resource"
 *          ),
 *          @OA\Property(
 *            property="avatar",
 *            type="object",
 *            @OA\Property(
 *                    property="link",
 *                    type="string",
 *                    example="http://localhost:8080/avatars/5f9f1b9b9b9b9b9b9b9b9b9b"
 *                )
 *            )
 *        )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="L'avatar n'a pas pu être trouvé"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="La ressource demandée n'a pas pu être créée"
 *     )
 *     )
 */
final class CreateAvatarAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $fileService = new FileService();
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        if (!isset($req->getUploadedFiles()['avatar'])) {
            return (throw new HttpInputNotValid($req, "L'avatar n'a pas pu être trouvé"));
        }

        $avatar = $fileService->createAvatar($req->getUploadedFiles()['avatar'], $args['id']);


        if (!isset($avatar)) {
            return (throw new HttpInternalServerErrorException($req, "La ressource demandée n'a pas pu être créée: " . $args['id']));
        }

        $rs->withHeader('Content-Type', 'application/json;charset=utf-8');

        $data = [
            'type' => 'resource',
            'avatar' => [
                "link" => $avatar
            ]
        ];

        $rs->withStatus(201)->getBody()->write(json_encode($data));
        return $rs;
    }
}
