<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\WorkLogDTO;

/**
 * Provides access to Docbee WorkLog records for a DocBeeDocument Task (sub-resource).
 *
 * @extends AbstractResource<WorkLogDTO>
 */
final class DocBeeDocumentTaskWorkLogResource extends AbstractResource
{
    protected string $dtoClass = WorkLogDTO::class;
    protected string $listKey  = 'workLog';

    public function __construct(HttpClientInterface $http, int $docBeeDocumentId, int $taskId)
    {
        $this->endpoint = "docBeeDocument/{$docBeeDocumentId}/task/{$taskId}/workLog";
        parent::__construct($http);
    }
}
