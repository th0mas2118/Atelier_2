<?php

namespace reunionou\auth\errors\exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpAlreadyExists extends HttpSpecializedException
{
    protected $code = 409;
    protected $message = "Already exists";
    protected string $title = '409 Already exists';
    protected string $description = 'The resource already exists...';
}
