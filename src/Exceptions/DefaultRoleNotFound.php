<?php


namespace Kodilab\LaravelBatuta\Exceptions;


use Throwable;

class DefaultRoleNotFound extends \RuntimeException
{
    public function __construct()
    {
        $message = 'No default role found. At least a default role must exist';

        parent::__construct($message, 0, null);
    }
}