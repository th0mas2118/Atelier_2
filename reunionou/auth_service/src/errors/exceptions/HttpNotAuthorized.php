<?php

namespace reunionou\auth\errors\exceptions;


use Slim\Exception\HttpSpecializedException;

class HttpNotAuthorized extends HttpSpecializedException
{
    protected $code = 401;
    protected $message = "Token non autorisé.";
    protected string $title = '401 Token non autorisé.';
    protected string $description = 'The server does not support...';
}
