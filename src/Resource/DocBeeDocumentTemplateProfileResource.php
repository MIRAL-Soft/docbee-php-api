<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DocBeeDocumentTemplateProfileDTO;

/**
 * Provides access to Docbee DocBeeDocumentTemplateProfile records.
 *
 * @extends AbstractResource<DocBeeDocumentTemplateProfileDTO>
 */
final class DocBeeDocumentTemplateProfileResource extends AbstractResource
{
    protected string $endpoint = 'v1/docBeeDocumentTemplateProfile';
    protected string $dtoClass = DocBeeDocumentTemplateProfileDTO::class;
    protected string $listKey  = 'docBeeDocumentTemplateProfile';
}
