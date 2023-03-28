<?php

namespace reunionou\auth\actions;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpInputNotValid;

final class SignUpAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        if (null === $rq->getParsedBody()) {
            $body = json_decode($rq->getBody()->getContents(), true);
        } else {
            $body = $rq->getParsedBody();
        }
        if (
            (!isset($body['email'])) || !v::email()->validate($body['email']) ||
            (!isset($body['username'])) || !v::stringVal()->validate($body['username']) ||
            (!isset($body['firstname'])) || !v::stringVal()->validate($body['firstname']) ||
            (!isset($body['lastname'])) || !v::stringVal()->validate($body['lastname']) ||
            (!isset($body['password'])) || !v::stringVal()->validate($body['password'])
        ) {
            return (throw new HttpInputNotValid($rq, "Les données envoyées ne sont pas valides"));
        }

        $db_service = new DbService($this->container->get('mongo_url'));
        $user = $db_service->signUp($body);
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(201);
        return $rs;
    }
}
