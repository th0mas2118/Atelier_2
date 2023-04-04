<?php


declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Factory\AppFactory;

use reunionou\frontwebapp\middlewares\Cors;
use reunionou\frontwebapp\actions\auth\SignInAction;
use reunionou\frontwebapp\actions\auth\SignUpAction;
use reunionou\frontwebapp\middlewares\ValidateToken;

use reunionou\frontwebapp\actions\auth\GetUserAction;
use reunionou\frontwebapp\actions\auth\SignOutAction;
use reunionou\frontwebapp\actions\comment\GetComment;
use reunionou\frontwebapp\actions\auth\GetFriendsList;
use reunionou\frontwebapp\actions\auth\GetUsersAction;
use reunionou\frontwebapp\actions\auth\AddFriendAction;
use reunionou\frontwebapp\actions\auth\DeleteUserAction;
use reunionou\frontwebapp\actions\auth\UpdateUserAction;
use reunionou\frontwebapp\actions\events\GetEventAction;
use reunionou\frontwebapp\actions\files\GetAvatarAction;

use reunionou\frontwebapp\actions\auth\DeleteFriendAction;


use reunionou\frontwebapp\actions\auth\GetUserEventsAction;
use reunionou\frontwebapp\actions\comment\PostCommentEvent;
use reunionou\frontwebapp\actions\events\CreateEventAction;
use reunionou\frontwebapp\actions\files\CreateAvatarAction;
use reunionou\frontwebapp\middlewares\ValidateUserProperty;
use reunionou\frontwebapp\actions\comment\GetCommentByIdEvent;
use reunionou\frontwebapp\actions\events\AddParticipantAction;
use reunionou\frontwebapp\actions\auth\GetUserInvitationsAction;
use reunionou\frontwebapp\actions\events\UpdateInvitationAction;
use reunionou\frontwebapp\actions\events\DeleteParticipantAction;
use reunionou\frontwebapp\actions\events\UpdateParticipationAction;
use reunionou\frontwebapp\actions\events\CreateEventUniqueInvitationAction;

use OpenApi\Annotations as OA;


$config = ['settings' => [
    'outputBuffering' => false,
]];

$app = AppFactory::create();
// add $config to slim $app
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

/**
 * @OA\Info(
 *     title="My First API",
 *     version="0.1"
 * )
 */


// AUTH SERVICE

$app->post('/signin', SignInAction::class)->setName('signin');
$app->post('/signout', SignOutAction::class)->setName('signout')->add(new ValidateToken());
$app->post('/signup', SignUpAction::class)->setName('signup');

$app->get('/user/{id}', GetUserAction::class)->setName('get_user');
$app->get('/user/{id}/invitations', GetUserInvitationsAction::class)->setName('get_user_invitations');
$app->get('/user/{id}/events', GetUserEventsAction::class)->setName('get_user_events');
$app->get('/user/{id}/friends', GetFriendsList::class)->setName('get_user_friends');
$app->get('/users', GetUsersAction::class)->setName('get_users');

$app->put('/user/{id}', UpdateUserAction::class)->setName('update_user')->add(new ValidateToken())->add(new ValidateUserProperty());
$app->put('/user/{id}/friends', AddFriendAction::class)->setName('add_user_friend')->add(new ValidateToken());

$app->delete('/user/{id}', DeleteUserAction::class)->setName('delete_user')->add(new ValidateToken());
$app->delete('/user/{id}/friends/{friend_id}', DeleteFriendAction::class)->setName('delete_user_friend')->add(new ValidateToken());


// EVENT SERVICE
$app->get('/events/{id}[/]', GetEventAction::class)->setName('get_event');

$app->post('/events[/]', CreateEventAction::class)->setName('create_event');

$app->patch('/events/{event_id}/participate[/]', UpdateParticipationAction::class)->setName("update_participation");

$app->get('/messages/{id}[/]', GetComment::class)->setName('get_comment');

$app->post('/events/{id}/participants[/]', AddParticipantAction::class)->setName('add_participant');

$app->delete('/events/{id}/participants[/]', DeleteParticipantAction::class)->setName('delete_participant');

// INVITATIONS
$app->patch('/invitations/{id}[/]', UpdateInvitationAction::class)->setName('update_invitation');

$app->post('/invitations/{id}/guest[/]', CreateEventUniqueInvitationAction::class)->setName("create_unique_invitation");

//COMMENT SERVICE
$app->get('/messages/{id}/event[/]', GetCommentByIdEvent::class)->setName('getCommentByIdEvent');
$app->post('/messages[/]', PostCommentEvent::class)->setName('postCommentEvent');

// FILES
$app->post('/avatars/{id}[/]', CreateAvatarAction::class)->setName('create_avatar');
$app->get('/avatars/{id}[/{width}[/{height}]]', GetAvatarAction::class)->setName('get_avatar');

$app->run();
