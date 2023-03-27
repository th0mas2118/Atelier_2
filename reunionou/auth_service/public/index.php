<?php

declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use reunionou\auth\actions\SigninAction;
use reunionou\auth\actions\SignOutAction;
use reunionou\auth\actions\SignUpAction;
use reunionou\auth\actions\ValidateAction;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../conf/settings.php');
$c = $builder->build();
$app = AppFactory::createFromContainer($c);

// $app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, false, false);

// Route pour la connexion

$app->get(
    '/hello',
    function (Request $rq, Response $rs, $args): Response {
        $rs->getBody()->write("Hello World");
        return $rs;
    }
);

$app->post('/signin', SigninAction::class)->setName('signin');
$app->post('/signup', SignUpAction::class)->setName('signup');
$app->post('/signout', SignOutAction::class)->setName('signout');
$app->get('/validate', ValidateAction::class)->setName('validate');

$app->run();
