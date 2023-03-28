<?php

namespace reunionou\auth\errors\exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpInputNotValid extends HttpSpecializedException
{
    protected $code = 400;
    protected $message = "Input not valid.";
    protected string $title = '400 Bad Request';
    protected string $description = 'The server did not understand the request...';
}
