<?php


namespace Kodilab\LaravelBatuta\Exceptions;


use Throwable;

class GrantedRoleNotFound extends \RuntimeException
{
    public function __construct()
    {
        $message = 'No granted role found. One, and only one, granted role must exist';

        parent::__construct($message, 0, null);
    }
}