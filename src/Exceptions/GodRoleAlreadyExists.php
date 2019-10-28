<?php


namespace Kodilab\LaravelBatuta\Exceptions;


use Throwable;

class GodRoleAlreadyExists extends \RuntimeException
{
    public function __construct()
    {
        $message = 'God role already exists.';

        parent::__construct($message, 0, null);
    }
}