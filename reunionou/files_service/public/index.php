<?php


declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';
// $settings = require_once __DIR__ . '/../conf/setting.php';


use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use reunionou\files\actions\GetAvatarAction;
use reunionou\files\actions\CreateAvatarAction;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/settings.php');
$c = $builder->build();
$app = AppFactory::createFromContainer($c);

// $app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');

$app->post('/avatars/{id}[/]', CreateAvatarAction::class)->setName("create_avatar");
$app->get('/avatars/{id}/{width}/{height}[/]', GetAvatarAction::class)->setName("get_avatar");

$app->run();
