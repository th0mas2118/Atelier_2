<?php

namespace reunionou\auth\actions;

use Firebase\JWT\JWT;
use reunionou\auth\errors\exceptions\HttpInputNotValid;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Respect\Validation\Validator as v;
use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpNotAuthorized;

final class SigninAction extends AbstractAction
{

    public function __invoke(Request $req, Response $rs, array $args): Response
    {
        $headerAuth = $req->getHeaders();
        if (!isset($headerAuth['Authorization'])) {
            return (throw new HttpInputNotValid($req, "No authorization header present"));
        }
        $headerAuth = $headerAuth['Authorization'];
        $userAuth = base64_decode(sscanf($headerAuth[0], "Basic %s")[0]);
        $username = strstr($userAuth, ':', true);
        $password = substr(strstr($userAuth, ':'), 1);
        if (
            (!isset($username)) || !v::stringVal()->validate($username) ||
            (!isset($password)) || !v::stringVal()->validate($password)
        ) {
            return (throw new HttpInputNotValid($req, "Les données envoyées ne sont pas valides"));
        }

        $db_service = new DbService($this->container->get('mongo_url'));
        $user = $db_service->signIn($username, $password);

        $user->id = strval($user->_id);


        $payload = ['iss' => 'http://api.auth.local', 'aud' => 'http://api.auth.local', 'iat' => time(), 'nbf' => time(), 'exp' => time() + 3600, 'username' => $user->username, "usermail" => $user->mail, 'lvl' => $user->level, 'uid' => $user->id];
        $token = JWT::encode($payload, $this->container->get('secret'), 'HS512');

        $db_service->updateToken($user->_id, $token);



        $data = [
            'type' => 'ressources',
            'user' => [
                'acces_token' => $token,
                'refresh_token' => $user->refresh_token
            ]
        ];

        $rs = $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
        $rs->getBody()->write(json_encode($data));
        return $rs;
    }
}
