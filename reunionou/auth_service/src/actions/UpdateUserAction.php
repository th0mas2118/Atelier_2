<?php

namespace reunionou\auth\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Respect\Validation\Validator as v;
use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpInputNotValid;


final class UpdateUserAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $id = $args['id'];
        if (null === $req->getParsedBody()) {
            $body = json_decode($req->getBody()->getContents(), true);
        } else {
            $body = $req->getParsedBody();
        }

        if (!isset($body['mail']) && !isset($body['adresse']) && !isset($body['firstname']) && !isset($body['lastname'])) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }


        $db_service = new DbService($this->container->get('mongo_url'));
        $db_service->updateUser($id, $body);

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(204);
        return $rs;
    }
}
