<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ProtocolTemplateProfileDTO;

/**
 * Provides access to Docbee ProtocolTemplateProfile records.
 *
 * @extends AbstractResource<ProtocolTemplateProfileDTO>
 */
final class ProtocolTemplateProfileResource extends AbstractResource
{
    protected string $endpoint = 'v1/protocolTemplateProfile';
    protected string $dtoClass = ProtocolTemplateProfileDTO::class;
    protected string $listKey  = 'protocolTemplateProfile';
}
