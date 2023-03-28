<?php

namespace reunionou\frontwebapp\middlewares;

use Respect\Validation\Validator as v;

use GuzzleHttp\Client;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Handlers\Strategies\RequestHandler;

final class ValidateInput
{
    public function __invoke(Request $rq, RequestHandlerInterface $handler): Response
    {
        $rs = new Response();
        $rs = $rs->withStatus(201)
            ->withHeader('Content-Type', 'application/json;charset=utf-8');

        $rs->getBody()->write(strval('input not valid'));
        if (!v::email()->validate($rq->getParsedBody()['client_mail'])) {
            return $rs;
        }
        if (!v::stringType()->notEmpty()->validate($rq->getParsedBody()['client_name'])) {
            return $rs;
        }
        if (!v::date()->validate(date('Y-m-d', strtotime($rq->getParsedBody()['delivery']['date'])))) {
            return $rs;
        }
        if (!(strtotime($rq->getParsedBody()['delivery']['date']) > strtotime('now'))) {
            return $rs;
        }
        if (!v::arrayType()->notEmpty()->validate($rq->getParsedBody()['items'])) {
            return $rs;
        }

        return $handler->handle($rq);
    }
}
