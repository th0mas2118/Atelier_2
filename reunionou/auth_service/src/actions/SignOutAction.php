<?php

namespace reunionou\auth\actions;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Firebase\JWT\ExpiredException;

use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpInputNotValid;


final class SignOutAction extends AbstractAction
{
    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $headerAuth = $req->getHeaders();
        if (!isset($headerAuth['Authorization'])) {
            return (throw new HttpInputNotValid($req, "No authorization header present"));
        }
        $headerAuth = $headerAuth['Authorization'][0];
        $userAuth = sscanf($headerAuth, "Bearer %s")[0];
        $db_service = new DbService($this->container->get('mongo_url'));
        $db_service->signOut($userAuth);

        $rs->withStatus(200);
        return $rs;
    }
}
