<?php

namespace TestMonitor\Mantis\Resources;

class Reporter extends Resource
{
    /**
     * The id of the reporter.
     *
     * @var string
     */
    public $id;

    /**
     * The name of the reporter.
     *
     * @var string
     */
    public $name;

    /**
     * The real name of the reporter.
     *
     * @var string
     */
    public $real_name;

    /**
     * The reporter mail.
     *
     * @var string
     */
    public $email;

    /**
     * Create a new resource instance.
     *
     * @param $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? null;
        $this->name = $attributes['name'] ?? null;
        $this->real_name = $attributes['real_name'] ?? null;
        $this->email = $attributes['email'] ?? null;
    }
}
