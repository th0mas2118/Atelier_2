<?php

namespace reunionou\auth\actions;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use reunionou\auth\services\DbService;

use Firebase\JWT\SignatureInvalidException;
use \reunionou\auth\errors\exceptions\HttpNotAuthorized;

function jsonPrint($printable)
{
    $json = json_encode($printable, JSON_PRETTY_PRINT);
    return "<pre>{$json}</pre>";
}
final class ValidateAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        if (!isset($rq->getHeaders()['Authorization'])) {
            return (throw new HttpNotAuthorized($rq, "No authorization header present"));
        }
        try {
            $h = $rq->getHeader('Authorization')[0];
            $tokenstring = sscanf($h, "Bearer %s")[0];
            $db_service = new DbService($this->container->get('mongo_url'));
            $db_service->validate($tokenstring);
            $token = JWT::decode($tokenstring, new Key($this->container->get('secret'), 'HS512'));
        } catch (ExpiredException $e) {
            return (throw new HttpNotAuthorized($rq, "Token expired"));
        } catch (SignatureInvalidException $e) {
            return (throw new HttpNotAuthorized($rq, "Token signature invalid"));
        } catch (BeforeValidException $e) {
            return (throw new HttpNotAuthorized($rq, "Token not valid yet"));
        } catch (\UnexpectedValueException $e) {
            return (throw new HttpNotAuthorized($rq, "Token malformed"));
        }


        $rs = $rs->withStatus(200)
            ->withHeader('Content-Type', 'application/json;charset=utf-8');

        $rs->getBody()->write(json_encode(['username' => $token->username, 'usermail' => $token->usermail, 'userlevel' => $token->lvl, 'uid' => $token->uid]));
        return $rs;
    }
}
