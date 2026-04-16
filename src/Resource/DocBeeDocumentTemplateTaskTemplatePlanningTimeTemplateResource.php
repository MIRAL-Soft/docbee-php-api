<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\PlanningTimeDTO;

/**
 * Provides access to Docbee PlanningTimeTemplate records for a DocBeeDocumentTemplate TaskTemplate (sub-resource).
 *
 * @extends AbstractResource<PlanningTimeDTO>
 */
final class DocBeeDocumentTemplateTaskTemplatePlanningTimeTemplateResource extends AbstractResource
{
    protected string $dtoClass = PlanningTimeDTO::class;
    protected string $listKey  = 'planningTimeTemplate';

    public function __construct(HttpClientInterface $http, int $documentTemplateId, int $taskTemplateId)
    {
        $this->endpoint = "v1/docBeeDocumentTemplate/{$documentTemplateId}/taskTemplate/{$taskTemplateId}/planningTimeTemplate";
        parent::__construct($http);
    }
}
