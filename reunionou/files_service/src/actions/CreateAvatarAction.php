<?php

namespace reunionou\files\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\files\services\FileService;
use reunionou\files\actions\AbstractAction;
use reunionou\files\errors\exceptions\HttpInputNotValid;
use Slim\Exception\HttpInternalServerErrorException;

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

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
