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

        if (!isset($body['avatar']) && !isset($body['adresse'])) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }


        $db_service = new DbService($this->container->get('mongo_url'));
        if (isset($body['avatar'])) {
            if (!v::stringVal()->validate($body['avatar'])) {
                return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
            }
            $db_service->modifyAvatar($id, $body['avatar']);
        }
        if (isset($body['adresse'])) {
            if (!v::stringVal()->validate($body['adresse'])) {
                return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
            }
            $db_service->modifyAdress($id, $body['adresse']);
        }

        $routeContext = RouteContext::fromRequest($req);
        $routeParser = $routeContext->getRouteParser();

        $rs = $rs->withStatus(204);
        return $rs;
    }
}
