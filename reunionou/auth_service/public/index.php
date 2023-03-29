<?php

declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use reunionou\auth\actions\AddFriendAction;
use Slim\Factory\AppFactory;
use reunionou\auth\actions\SigninAction;
use reunionou\auth\actions\SignUpAction;
use reunionou\auth\actions\GetUserAction;
use reunionou\auth\actions\SignOutAction;
use reunionou\auth\actions\ValidateAction;
use reunionou\auth\actions\DeleteUserAction;
use reunionou\auth\actions\UpdateUserAction;


$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../conf/settings.php');
$c = $builder->build();
$app = AppFactory::createFromContainer($c);

// $app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');

// Route pour la connexion

$app->post('/signin', SigninAction::class)->setName('signin');
$app->post('/signup', SignUpAction::class)->setName('signup');
$app->post('/signout', SignOutAction::class)->setName('signout');
$app->get('/validate', ValidateAction::class)->setName('validate');
$app->get('/user/{id}', GetUserAction::class)->setName('get_user');


$app->put('/user/{id}/friends', AddFriendAction::class)->setName('add_friendd');
$app->put('/user/{id}', UpdateUserAction::class)->setName('update_user');
$app->delete('/user/{id}', DeleteUserAction::class)->setName('delete_user');

$app->post('/validate', ValidateAction::class)->setName('validate');

$app->run();
