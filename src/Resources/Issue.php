<?php

namespace TestMonitor\Mantis\Resources;

class Issue extends Resource
{
    /**
     * The id of the issue.
     *
     * @var string
     */
    public $id;

    /**
     * The summary of the issue.
     *
     * @var string
     */
    public $summary;

    /**
     * The description of the issue.
     *
     * @var string
     */
    public $description;

    /**
     * The issue category.
     *
     * @var string
     */
    public $category;

    /**
     * The id of the project.
     *
     * @var string
     */
    public $projectId;

    /**
     * Create a new resource instance.
     *
     * @param $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? null;
        $this->summary = $attributes['summary'];
        $this->description = $attributes['description'];
        $this->category = $attributes['category'];
        $this->projectId = $attributes['projectId'] ?? null;
    }
}
