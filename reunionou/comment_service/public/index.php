<?php

declare(strict_types=1);
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use reunionou\comment\actions\GetCommentAction;
use reunionou\src\service\utils\CommentService;
use reunionou\comment\actions\CreateCommentAction;
use reunionou\comment\actions\DeleteCommentAction;
use reunionou\comment\actions\UpdateCommentAction;
use reunionou\comment\actions\GetCommentByIdEventAction;


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


$app->get('/messages/{id}[/]',GetCommentAction::class)->setName('getComment');

$app->get('/messages/{id}/event[/]',GetCommentByIdEventAction::class)->setName('getCommentByIdEventAction');

$app->post('/messages[/]', CreateCommentAction::class)->setName('createComment');

$app->put('/messages/{id}[/]', UpdateCommentAction::class)->setName('updateComment');

$app->delete('/messages/{id}[/]', DeleteCommentAction::class)->setName('deleteComment');

$app->get('/hello[/]', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello,");
    return $response;
});


$app->run();