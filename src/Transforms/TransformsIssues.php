<?php

namespace TestMonitor\Mantis\Transforms;

use TestMonitor\Mantis\Resources\Issue;

trait TransformsIssues
{
    /**
     * @param \TestMonitor\Mantis\Resources\Issue $issue
     * @param string $projectId
     *
     * @return array
     */
    protected function toMantisIssue(Issue $issue, $projectId = null): array
    {
        return [
            'summary' => $issue->summary,
            'description' => $issue->description,
            'category' => [
                'name' => $issue->category,
            ],
            'project' => [
                'id' => $projectId ?? $issue->projectId,
            ],
        ];
    }

    /**
     * @param array $issue
     *
     * @return \TestMonitor\Mantis\Resources\Issue
     */
    protected function fromMantisIssue(array $issue): Issue
    {
        return new Issue([
            'id' => $issue['id'],
            'summary' => $issue['summary'] ?? '',
            'description' => $issue['description'] ?? '',
            'category' => $issue['category']['name'],
            'projectId' => $issue['project']['id'],
        ]);
    }
}
