<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\PlanningTimeTemplateDTO;

/**
 * Provides access to Docbee PlanningTimeTemplate records for a DocBeeDocumentTemplate TaskTemplate (sub-resource).
 *
 * @extends AbstractResource<PlanningTimeTemplateDTO>
 */
final class DocBeeDocumentTemplateTaskTemplatePlanningTimeTemplateResource extends AbstractResource
{
    protected string $dtoClass = PlanningTimeTemplateDTO::class;
    protected string $listKey  = 'planningTimeTemplate';

    public function __construct(HttpClientInterface $http, int $documentTemplateId, int $taskTemplateId)
    {
        $this->endpoint = "v1/docBeeDocumentTemplate/{$documentTemplateId}/taskTemplate/{$taskTemplateId}/planningTimeTemplate";
        parent::__construct($http);
    }
}
