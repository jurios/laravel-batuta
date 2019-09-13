<?php


namespace Kodilab\LaravelBatuta\Exceptions;


use Throwable;

class ActionAlreadyExists extends \RuntimeException
{
    /**
     * ActionAlreadyExists constructor.
     * @param string $action
     * @param string $resource
     */
    public function __construct(string $action, string $resource)
    {
        $message = 'Action \'' . $action . ' ' . $resource . '\' already exists';

        parent::__construct($message, 0, null);
    }
}