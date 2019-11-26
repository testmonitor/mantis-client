<?php

namespace TestMonitor\Mantis\Transforms;

use TestMonitor\Mantis\Resources\Issue;

trait TransformsIssues
{
    /**
     * @param \TestMonitor\Mantis\Resources\Issue $issue
     *
     * @return array
     */
    protected function toMantisIssue(Issue $issue): array
    {
        return [
            'summary' => $issue->summary,
            'description' => $issue->description,
            'category' => [
                'name' => $issue->category,
            ],
            'project' => [
                'id' => $issue->projectId,
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
        return new Issue(
            $issue['summary'] ?? '',
            $issue['description'] ?? '',
            $issue['category']['name'],
            $issue['project']['id'],
            $issue['id']
        );
    }
}
