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
     * @param string $projectId
     *
     * @return Issue[]
     */
    public function issues($projectId)
    {
        $result = [];
        $page_nb = 1;
        do {
            $result_page = $this->get('issues', ['query' => ['project_id' => $projectId, 'page'=>$page_nb]]);
            $result = array_merge_recursive($result, $result_page);
            $page_nb++;
        } while (! empty($result_page['issues']));

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
