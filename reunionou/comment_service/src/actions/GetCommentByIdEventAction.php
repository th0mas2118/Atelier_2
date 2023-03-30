<?php

namespace reunionou\comment\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\comment\actions\AbstractAction;
use reunionou\comment\errors\exceptions\HttpNotFound;
use reunionou\comment\service\CommentService;

final class GetCommentByIdEventAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $commentService = new CommentService($this->container->get('mongo_url'));
        $comments = $commentService->getEventComments($args['id']);
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();


        if (!isset($comments)) {
            return (throw new HttpNotFound($req, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');

        $commentList = [];

        foreach ($comments as $comment) {
            $comment->uri = $routeParser->urlFor('getComment', ['id' => strval($comment["_id"])]);
            $comment->id = strval($comment["_id"]);
            unset($comment["_id"]);
            array_push($commentList, $comment);
        }

        $data = [
            'type' => 'collection',
            'count' => count($commentList),
            'comments' => $commentList,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('getCommentByIdEventAction', ['id' => $args['id']])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
