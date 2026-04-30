<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DocBeeDocumentTaskDTO;
use miralsoft\docbee\api\DTO\DocumentTaskDeletionCheckDTO;

/**
 * Provides access to Docbee Task records for a DocBeeDocument (sub-resource).
 *
 * @extends AbstractResource<DocBeeDocumentTaskDTO>
 */
final class DocBeeDocumentTaskResource extends AbstractResource
{
    protected string $dtoClass = DocBeeDocumentTaskDTO::class;
    protected string $listKey  = 'task';

    public function __construct(HttpClientInterface $http, int $docBeeDocumentId)
    {
        $this->endpoint = "docBeeDocument/{$docBeeDocumentId}/task";
        parent::__construct($http);
    }

    /**
     * Updates the description of a task and returns the updated DTO.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function updateDescription(int $taskId, string $description): DocBeeDocumentTaskDTO
    {
        /** @var DocBeeDocumentTaskDTO */
        return $this->update($taskId, ['description' => $description]);
    }

    /**
     * Checks whether a task can be safely deleted.
     *
     * A task can be deleted only when it has no associated work logs, planning
     * times, or materials.  This method performs three lightweight count queries
     * (one per sub-resource) and returns a {@see DocumentTaskDeletionCheckDTO}
     * that exposes the result via {@see DocumentTaskDeletionCheckDTO::canDelete()}
     * and {@see DocumentTaskDeletionCheckDTO::getBlockers()}.
     *
     * ```php
     * $check = $client->tasks($docId)->canBeDeleted($taskId);
     * if ($check->canDelete()) {
     *     $client->tasks($docId)->delete($taskId);
     * }
     * ```
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function canBeDeleted(int $taskId): DocumentTaskDeletionCheckDTO
    {
        $base = "{$this->endpoint}/{$taskId}";

        $workLogs      = (int) (($this->http->get("{$base}/workLog?limit=0&offset=0"))['totalCount'] ?? 0);
        $planningTimes = (int) (($this->http->get("{$base}/planningTime?limit=0&offset=0"))['totalCount'] ?? 0);
        $materials     = (int) (($this->http->get("{$base}/material?limit=0&offset=0"))['totalCount'] ?? 0);

        return new DocumentTaskDeletionCheckDTO($workLogs, $planningTimes, $materials);
    }
}
