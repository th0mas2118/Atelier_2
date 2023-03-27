<?php

namespace reunionou\comment\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\comment\service\CommentService;
use reunionou\comment\actions\AbstractAction;
use reunionou\comment\errors\exceptions\HttpNotFound;


final class GetCommentAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {

        $message = new CommentService($this->container->get('mongo_url'));
        $comment = $message->getComment($args['id']);
        
        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();

        if (!isset($comment)) {
            return (throw new HttpNotFound($request, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $data = [
            'type' => 'resource',
            'comment' => $comment,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('getComment', ['id' => strval($comment["_id"])])
                ],
            ]
        ];





        $response = $response->withHeader('Content-type', 'application/json;charset=utf-8')->withStatus(202);
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
