<?php


declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Factory\AppFactory;
use reunionou\frontwebapp\actions\SignInAction;
use reunionou\frontwebapp\actions\GetUserAction;
use reunionou\frontwebapp\actions\SignOutAction;

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, false, false);

/**
 * configuring API Routes
 */

$app->addBodyParsingMiddleware();

$app->add(reunionou\frontwebapp\middlewares\Cors::class);

$app->options(
    '/{routes:.+}',
    function (
        Request $rq,
        Response $rs,
        array $args
    ): Response {
        return $rs;
    }
);

$app->get(
    '/hello',
    function (Request $rq, Response $rs, $args): Response {
        $rs->getBody()->write("Hello World");
        return $rs;
    }
);

$app->post('/signin', SignInAction::class)->setName('signin');
$app->post('/signout', SignOutAction::class)->setName('signout');

$app->get('/user/{id}', GetUserAction::class)->setName('getUser');

$app->run();
