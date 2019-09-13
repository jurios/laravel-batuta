<?php


namespace Kodilab\LaravelBatuta\Exceptions;



class GodRoleNotFound extends \RuntimeException
{
    public function __construct()
    {
        $message = 'No god role found. One, and only one, god role must exist';

        parent::__construct($message, 0, null);
    }
}