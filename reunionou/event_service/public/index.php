<?php

declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';
// $settings = require_once __DIR__ . '/../conf/setting.php';

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use reunionou\event\actions\events\GetEventAction;
use reunionou\event\actions\events\GetEventsAction;
use reunionou\event\actions\events\CreateEventAction;
use reunionou\event\actions\events\DeleteEventAction;
use reunionou\event\actions\events\UpdateEventAction;
use reunionou\event\actions\comments\GetEventCommentsAction;
use reunionou\event\actions\events\UpdateParticipationAction;
use reunionou\event\actions\invitations\CreateInvitationAction;
use reunionou\event\actions\invitations\DeleteInvitationAction;
use reunionou\event\actions\invitations\GetEventInvitationsAction;
use reunionou\event\actions\invitations\GetInvitationAction;
use reunionou\event\actions\invitations\UpdateInvitationAction;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/settings.php');
$c = $builder->build();
$app = AppFactory::createFromContainer($c);

// $app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');

// EVENTS
$app->get('/events[/]', GetEventsAction::class)->setName("get_events");
$app->get('/events/{id}[/]', GetEventAction::class)->setName("get_event");
$app->get('/events/{id}/comments[/]', GetEventCommentsAction::class)->setName("get_event_comments");
$app->get('/events/{id}/invitations[/]', GetEventInvitationsAction::class)->setName("get_event_invitations");

$app->post('/events[/]', CreateEventAction::class)->setName("create_event");

$app->put('/events/{id}[/]', UpdateEventAction::class)->setName("update_event");

$app->patch('/events/{event_id}/participate[/]', UpdateParticipationAction::class)->setName("update_participation");

$app->delete('/events/{id}[/]', DeleteEventAction::class)->setName("delete_event");

// COMMENTS

$app->get('/comments/{id}[/]', GetEventCommentsAction::class)->setName("get_comment");

// INVITATIONS

$app->get('/invitation/{id}[/]', GetInvitationAction::class)->setName("get_invitation");

$app->post('/invitation/{id}[/]', CreateInvitationAction::class)->setName("create_invitation");

$app->put('/invitation/{id}[/]', UpdateInvitationAction::class)->setName("update_invitation");

$app->delete('/invitation/{id}[/]', DeleteInvitationAction::class)->setName("delete_invitation");

$app->run();
