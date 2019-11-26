<?php

namespace TestMonitor\Mantis\Actions;

trait ManagesAttachments
{
    /**
     * Add a new attachment.
     *
     * @param string $path
     * @param string $issueId
     *
     * @return string
     */
    public function addAttachment(string $path, string $issueId): string
    {
        $result = $this->post("issues/{$issueId}/files", ['json' => [
            'files' => [
                [
                    'name' => basename($path),
                    'content' => file_get_contents($path),
                ],
            ],
        ]]);

        return $result;
    }
}
