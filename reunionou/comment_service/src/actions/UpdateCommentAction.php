<?php

namespace reunionou\comment\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use reunionou\comment\service\CommentService;
use reunionou\comment\actions\AbstractAction;
use reunionou\comment\errors\exceptions\HttpNotFound;

final class UpdateCommentAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        if (null === $request->getParsedBody()) {
            $body = json_decode($request->getBody()->getContents(), true);
        } else {
            $body = $request->getParsedBody();
        }


        $message = new CommentService($this->container->get('mongo_url'));
        $comment = $message->getComment($args['id']);

        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();

        if (!isset($comment)) {
            return (throw new HttpNotFound($request, "L'identifiant de la ressource demandée ne correspond à aucune ressource disponible: " . $args['id']));
        }

        $message->updateComment($args['id'], $body);

        $updatedComment = $message->getComment($args['id']);

        $data = [
            'type' => 'resource',
            'comment' => $updatedComment,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('updateComment', ['id' => strval($args['id'])])
                ],
            ]
        ];

        $response = $response->withHeader('Content-type', 'application/json;charset=utf-8')->withStatus(202);
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
