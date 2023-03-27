<?php

namespace reunionou\event\errors\exceptions;


use Slim\Exception\HttpSpecializedException;

class HttpNotFound extends HttpSpecializedException
{
    protected $code = 404;
    protected $message = "Not Found";
    protected string $title = '404 Not Found';
    protected string $description = 'The server does not support...';
}
