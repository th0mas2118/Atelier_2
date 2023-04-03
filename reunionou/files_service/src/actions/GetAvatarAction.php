<?php

namespace reunionou\files\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\files\services\FileService;
use reunionou\files\actions\AbstractAction;
use Slim\Exception\HttpInternalServerErrorException;
use Intervention\Image\ImageManagerStatic as Image;

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
