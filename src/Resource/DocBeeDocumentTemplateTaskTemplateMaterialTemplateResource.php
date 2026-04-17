<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\MaterialTemplateDTO;

/**
 * Provides access to Docbee MaterialTemplate records for a DocBeeDocumentTemplate TaskTemplate (sub-resource).
 *
 * @extends AbstractResource<MaterialTemplateDTO>
 */
final class DocBeeDocumentTemplateTaskTemplateMaterialTemplateResource extends AbstractResource
{
    protected string $dtoClass = MaterialTemplateDTO::class;
    protected string $listKey  = 'materialTemplate';

    public function __construct(HttpClientInterface $http, int $documentTemplateId, int $taskTemplateId)
    {
        $this->endpoint = "v1/docBeeDocumentTemplate/{$documentTemplateId}/taskTemplate/{$taskTemplateId}/materialTemplate";
        parent::__construct($http);
    }
}
