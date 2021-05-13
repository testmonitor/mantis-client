<?php

namespace TestMonitor\Mantis\Actions;

use TestMonitor\Mantis\Validator;
use TestMonitor\Mantis\Resources\Issue;
use TestMonitor\Mantis\Transforms\TransformsIssues;

trait ManagesIssues
{
    use TransformsIssues;

    /**
     * Get a list of of issues.
     *
     * @param int|null $projectId
     * @param int $limit
     * @param int $page
     *
     * @throws \TestMonitor\Mantis\Exceptions\InvalidDataException
     * @return Issue[]
     */
    public function issues(int $projectId = null, int $limit = 50, int $page = 1)
    {
        $result = $this->get('issues', array_filter(['query' => [
            'project_id' => $projectId,
            'page_size' => $limit,
            'page' => $page,
        ]]));

        Validator::isArray($result);

        return array_map(function ($issue) {
            return $this->fromMantisIssue($issue);
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

        Validator::isArray($result);

        return $this->fromMantisIssue($result['issues'][0]);
    }

    /**
     * Create a new issue.
     *
     * @param \TestMonitor\Mantis\Resources\Issue $issue
     * @param string $projectId
     *
     * @return Issue
     */
    public function createIssue(Issue $issue, $projectId)
    {
        $result = $this->post('issues', [
            'json' => $this->toMantisIssue($issue, $projectId),
        ]);

        return $this->fromMantisIssue($result['issue']);
    }
}
