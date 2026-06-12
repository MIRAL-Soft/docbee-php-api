<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ProtocolTemplateTypeDTO;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee ProtocolTemplateType records.
 *
 * @extends AbstractResource<ProtocolTemplateTypeDTO>
 */
final class ProtocolTemplateTypeResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'protocolTemplateType';
    protected string $dtoClass = ProtocolTemplateTypeDTO::class;
    protected string $listKey  = 'protocolTemplateType';

    /** @return array<string, mixed> */
    public function getNavigationItems(): array { return $this->http->get("{$this->endpoint}/navigationItems"); }
}
