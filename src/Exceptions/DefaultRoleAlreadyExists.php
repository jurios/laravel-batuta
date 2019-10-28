<?php


namespace Kodilab\LaravelBatuta\Exceptions;


use Throwable;

class DefaultRoleAlreadyExists extends \RuntimeException
{
    public function __construct()
    {
        $message = 'Default role already exists.';

        parent::__construct($message, 0, null);
    }
}