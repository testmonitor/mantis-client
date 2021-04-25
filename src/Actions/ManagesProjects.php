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
     * @param int|string $identifier
     *
     * @return Project
     */
    public function project($identifier)
    {
        if (! is_int($identifier)) {
            $result = $this->projects();
            foreach ($result as $project) {
                if ($project->name == $identifier) {
                    $identifier = $project->id;
                    break;
                }
            }
        }

        $result = $this->get("projects/{$identifier}");

        return $this->fromMantisProject($result['projects'][0]);
    }
}
