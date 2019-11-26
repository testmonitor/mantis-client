<?php

namespace TestMonitor\Mantis\Exceptions;

class UnauthorizedException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
}
