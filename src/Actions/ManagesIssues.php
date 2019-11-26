<?php

namespace TestMonitor\Mantis\Actions;

use TestMonitor\Mantis\Resources\Issue;
use TestMonitor\Mantis\Transforms\TransformsIssues;

trait ManagesIssues
{
    use TransformsIssues;

    /**
     * Get a list of of issues.
     *
     * @param string $projectId
     *
     * @return Issue[]
     */
    public function issues($projectId)
    {
        $result = $this->get('issues', ['project_id' => $projectId]);

        return array_map(function ($project) {
            return $this->fromMantisIssue($project);
        }, $result['issues']);
    }

    /**
     * Get a single issue.
     *
     * @param string $id
     *
     * @return Issue
     */
    public function issue($id)
    {
        $result = $this->get("issues/{$id}");

        return $this->fromMantisIssue($result['issues'][0]);
    }

    /**
     * Create a new issue.
     *
     * @param \TestMonitor\Mantis\Resources\Issue $issue
     *
     * @return Issue
     */
    public function createIssue(Issue $issue)
    {
        $result = $this->post('issues', [
            'json' => $this->toMantisIssue($issue),
        ]);

        return $this->fromMantisIssue($result['issue']);
    }
}
