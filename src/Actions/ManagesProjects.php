<?php

namespace TestMonitor\Mantis\Actions;

use TestMonitor\Mantis\Resources\Project;
use TestMonitor\Mantis\Transforms\TransformsProjects;

trait ManagesProjects
{
    use TransformsProjects;

    /**
     * Get a list of of projects.
     *
     * @return Project[]
     */
    public function projects()
    {
        $result = $this->get('projects');

        return array_map(function ($project) {
            return $this->fromMantisProject($project);
        }, $result['projects']);
    }

    /**
     * Get a single project.
     *
     * @param string $id
     *
     * @return Project
     */
    public function project($id)
    {
        $result = $this->get("projects/{$id}");

        return $this->fromMantisProject($result['projects'][0]);
    }
}
