<?php

namespace TestMonitor\Mantis\Resources;

class Project extends Resource
{
    /**
     * The id of the project.
     *
     * @var string
     */
    public $id;

    /**
     * The name of the project.
     *
     * @var string
     */
    public $name;

    /**
     * The categories of the project.
     *
     * @var array
     */
    public $categories;

    /**
     * Create a new resource instance.
     *
     * @param string $id
     * @param string $name
     * @param array $categories
     */
    public function __construct($id, string $name, array $categories = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->categories = $categories;
    }
}
