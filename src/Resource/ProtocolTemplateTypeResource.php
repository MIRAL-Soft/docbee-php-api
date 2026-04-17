<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ProtocolTemplateTypeDTO;

/**
 * Provides access to Docbee ProtocolTemplateType records.
 *
 * @extends AbstractResource<ProtocolTemplateTypeDTO>
 */
final class ProtocolTemplateTypeResource extends AbstractResource
{
    protected string $endpoint = 'v1/protocolTemplateType';
    protected string $dtoClass = ProtocolTemplateTypeDTO::class;
    protected string $listKey  = 'protocolTemplateType';

    public function getNavigationItems(): array { return $this->http->get("{$this->endpoint}/navigationItems"); }
}
