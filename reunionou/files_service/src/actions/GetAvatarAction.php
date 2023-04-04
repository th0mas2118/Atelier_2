<?php

namespace reunionou\files\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\files\services\FileService;
use reunionou\files\actions\AbstractAction;
use Slim\Exception\HttpInternalServerErrorException;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * @OA\Get(
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
 *     @OA\Parameter(
 *         name="width",
 *         in="query",
 *         description="Largeur de l'image",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             example="100"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="height",
 *         in="query",
 *         description="Hauteur de l'image",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             example="100"
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Avatar de l'utilisateur"
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Avatar non trouvé"
 *     )
 * )
 */
final class GetAvatarAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $fileService = new FileService();
        $avatar = $fileService->getAvatar($args['id']);

        $width = isset($args['width']) ? ($args['width'] == "default" ? "default" : intval($args['width'])) : null;
        $height = isset($args['height']) ? ($args['height'] == "default" ? "default" : intval($args['height'])) : null;

        if (file_exists($avatar)) {

            if ($width && $height && $width != "default" && $height != "default") {
                $image = Image::make($avatar)->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $image = Image::make($avatar);
            }

            $response = new Response();

            $imageContent = $image->encode('webp', 80)->getEncoded();
            $response->getBody()->write($imageContent);

            return $response
                ->withHeader('Content-Type', $image->mime())
                ->withHeader('Content-Length', strlen($imageContent));
        } else {
            return $rs->withStatus(404);
        }
    }
}
