<?php

namespace TestMonitor\Mantis\Transforms;

use TestMonitor\Mantis\Resources\Project;

trait TransformsProjects
{
    /**
     * @param array $project
     *
     * @return \TestMonitor\Mantis\Resources\Project
     */
    protected function fromMantisProject(array $project): Project
    {
        return new Project([
            'id' => $project['id'],
            'name' => $project['name'],
            'categories' => array_map(function ($category) {
                return ['id' => $category['id'], 'name' => $category['name']];
            }, $project['categories'] ?? []),
        ]);
    }
}
