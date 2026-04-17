<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DocBeeDocumentTemplateTaskTemplateDTO;

/**
 * Provides access to Docbee TaskTemplate records for a DocBeeDocumentTemplate (sub-resource).
 *
 * @extends AbstractResource<DocBeeDocumentTemplateTaskTemplateDTO>
 */
final class DocBeeDocumentTemplateTaskTemplateResource extends AbstractResource
{
    protected string $dtoClass = DocBeeDocumentTemplateTaskTemplateDTO::class;
    protected string $listKey  = 'taskTemplate';

    public function __construct(HttpClientInterface $http, int $documentTemplateId)
    {
        $this->endpoint = "v1/docBeeDocumentTemplate/{$documentTemplateId}/taskTemplate";
        parent::__construct($http);
    }
}
