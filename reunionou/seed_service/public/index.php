<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use reunionou\seed\actions\SeedEventsAction;
use Slim\Factory\AppFactory;
use reunionou\seed\actions\SeedUsersAction;

require_once __DIR__ . '/../vendor/autoload.php';
// $settings = require_once __DIR__ . '/../conf/setting.php';


$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/settings.php');
$c = $builder->build();
$app = AppFactory::createFromContainer($c);

// $app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');


$app->post('/users/seed', SeedUsersAction::class)->setName('seed_users');
$app->post('/events/seed', SeedEventsAction::class)->setName('seed_events');

$app->run();
