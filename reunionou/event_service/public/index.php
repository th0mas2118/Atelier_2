<?php

declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';
// $settings = require_once __DIR__ . '/../conf/setting.php';

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use reunionou\event\actions\GetEventAction;
use reunionou\event\actions\CreateEventAction;
use reunionou\event\actions\CreateEventInvitationAction;
use reunionou\event\actions\DeleteEventAction;
use reunionou\event\actions\DeleteEventUniqueInvitation;
use reunionou\event\actions\GetEventCommentsAction;
use reunionou\event\actions\GetEventsAction;
use reunionou\event\actions\GetEventUniqueInvitationAction;
use reunionou\event\actions\UpdateEventAction;
use reunionou\event\actions\UpdateEventUniqueInvitationAction;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/settings.php');
$c = $builder->build();
$app = AppFactory::createFromContainer($c);

// $app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');

$app->get('/events[/]', GetEventsAction::class)->setName("get_events");
$app->get('/events/{id}[/]', GetEventAction::class)->setName("get_event");
$app->get('/events/{id}/comments[/]', GetEventCommentsAction::class)->setName("get_event_comments");
$app->get('/comments/{id}[/]', GetEventAction::class)->setName("get_comment");
$app->get('/invitation/{id}[/]', GetEventUniqueInvitationAction::class)->setName("get_invitation");

$app->post('/invitation/{id}[/]', CreateEventUniqueInvitationAction::class)->setName("generate_unique_invite_link");
$app->post('/events[/]', CreateEventAction::class)->setName("create_event");

$app->put('/events/{id}[/]', UpdateEventAction::class)->setName("update_event");
$app->put('/invitation/{id}[/]', UpdateEventUniqueInvitationAction::class)->setName("update_invitation");

$app->delete('/events/{id}[/]', DeleteEventAction::class)->setName("delete_event");
$app->delete('/invitation/{id}[/]', DeleteEventUniqueInvitation::class)->setName("delete_invitation");

$app->run();
