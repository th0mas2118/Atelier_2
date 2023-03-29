<?php


declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Factory\AppFactory;
use reunionou\frontwebapp\actions\SignInAction;
use reunionou\frontwebapp\actions\SignUpAction;
use reunionou\frontwebapp\actions\GetUserAction;
use reunionou\frontwebapp\actions\SignOutAction;
use reunionou\frontwebapp\actions\UpdateUserAction;
use reunionou\frontwebapp\middlewares\ValidateToken;

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');

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
$app->post('/signout', SignOutAction::class)->setName('signout')->add(new ValidateToken());
$app->post('/signup', SignUpAction::class)->setName('signup');

$app->get('/user/{id}', GetUserAction::class)->setName('get_user');
$app->put('/user/{id}', UpdateUserAction::class)->setName('update_user')->add(new ValidateToken());
$app->delete('/user/{id}', DeleteUserAction::class)->setName('delete_user')->add(new ValidateToken());

$app->run();
