<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\WorkLogDTO;

/**
 * Provides access to Docbee WorkLogTemplate records for a DocBeeDocumentTemplate TaskTemplate (sub-resource).
 *
 * @extends AbstractResource<WorkLogDTO>
 */
final class DocBeeDocumentTemplateTaskTemplateWorkLogTemplateResource extends AbstractResource
{
    protected string $dtoClass = WorkLogDTO::class;
    protected string $listKey  = 'workLogTemplate';

    public function __construct(HttpClientInterface $http, int $documentTemplateId, int $taskTemplateId)
    {
        $this->endpoint = "v1/docBeeDocumentTemplate/{$documentTemplateId}/taskTemplate/{$taskTemplateId}/workLogTemplate";
        parent::__construct($http);
    }
}
