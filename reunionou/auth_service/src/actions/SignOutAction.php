<?php

namespace reunionou\auth\actions;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\services\DbService;
use reunionou\auth\errors\exceptions\HttpNotAuthorized;


final class SignOutAction extends AbstractAction
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
            $response = $db_service->signOut($tokenstring);
            $rs->getStatusCode($response);
        } catch (ExpiredException $e) {
            return (throw new HttpNotAuthorized($rq, "Token expired"));
        } catch (SignatureInvalidException $e) {
            return (throw new HttpNotAuthorized($rq, "Token signature invalid"));
        } catch (BeforeValidException $e) {
            return (throw new HttpNotAuthorized($rq, "Token not valid yet"));
        } catch (\UnexpectedValueException $e) {
            return (throw new HttpNotAuthorized($rq, "Token malformed"));
        }

        return $rs;
    }
}
