<?php

namespace reunionou\comment\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\comment\actions\AbstractAction;
use reunionou\comment\service\CommentService;
use reunionou\comment\errors\exceptions\HttpInputNotValid;


final class CreateCommentAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        if (
            (!isset($body["content"]) || !v::stringVal()->validate($body["content"])) ||
            (!isset($body["event_id"]) || !v::stringVal()->validate($body["event_id"])) ||
            (!isset($body["member_id"]) || !v::stringVal()->validate($body["member_id"])))
        {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }

        $commentService = new CommentService($this->container->get('mongo_url'));
        $comment = $commentService->createComment($body);
        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
        print_r($comment);
        $data = [
            'type' => 'resource',
            'event' => $body,
            'links' => [
                'self' => [
                    'href' => $routeParser->urlFor('getComment', ['id' => $comment])
                ],
            ]
        ];

        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
