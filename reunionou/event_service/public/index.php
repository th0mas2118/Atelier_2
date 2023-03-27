<?php

declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';
// $settings = require_once __DIR__ . '/../conf/setting.php';

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Handlers\ErrorHandler;
use lbs\auth\actions\SigninAction;
use lbs\auth\actions\ValidateAction;
use reunionou\event\actions\GetEventAction;
use reunionou\event\actions\CreateEventAction;
use reunionou\event\errors\renderer\JsonErrorRenderer;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/settings.php');
$c = $builder->build();
$app = AppFactory::createFromContainer($c);

// $app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');

$app->get('/events/{id}', GetEventAction::class)->setName("get_event");
$app->post('/events', CreateEventAction::class)->setName("create_event");

$app->run();
