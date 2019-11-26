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
     * @param string $summary
     * @param string $description
     * @param string $category
     * @param string $projectId
     * @param string $id
     */
    public function __construct(
        string $summary,
        string $description,
        string $category,
        string $projectId,
        ?string $id = null
    ) {
        $this->id = $id;
        $this->summary = $summary;
        $this->description = $description;
        $this->category = $category;
        $this->projectId = $projectId;
    }
}
