<?php

namespace reunionou\auth\actions;

use Firebase\JWT\JWT;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpNotAuthorized;

final class SigninAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $headerAuth = $rq->getHeaders()['Authorization'][0];
        $userAuth = base64_decode(sscanf($headerAuth, "Basic %s")[0]);
        if (!$headerAuth || $userAuth == ':') {
            return (throw new HttpNotAuthorized($rq, "No authorization header present"));
        }
        $userAuth = base64_decode(sscanf($headerAuth, "Basic %s")[0]);
        $username = strstr($userAuth, ':', true);
        $password = substr(strstr($userAuth, ':'), 1);

        $db_service = new DbService($this->container->get('mongo_url'));
        $user = $db_service->signIn($username, $password);

        $payload = ['iss' => 'http://api.auth.local', 'aud' => 'http://api.auth.local', 'iat' => time(), 'nbf' => time(), 'exp' => time() + 3600, 'username' => $user->username, "usermail" => $user->mail, 'lvl' => $user->level, 'uid' => $user->_id];
        $token = JWT::encode($payload, $this->container->get('secret'), 'HS512');

        $db_service->updateToken($user->_id, $token);

        $rs = $rs->withStatus(201)
            ->withHeader('Content-Type', 'application/json;charset=utf-8');

        $rs->getBody()->write(json_encode(['token' => $token, 'refresh_token' => $user->refresh_token]));
        return $rs;
    }
}
