<?php


namespace Kodilab\LaravelBatuta\Exceptions;


use Throwable;

class ActionNotFound extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}