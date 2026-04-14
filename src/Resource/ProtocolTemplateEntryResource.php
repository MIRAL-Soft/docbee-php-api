<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ProtocolTemplateEntryDTO;

/**
 * Provides access to Docbee ProtocolTemplateEntry records.
 *
 * @extends AbstractResource<ProtocolTemplateEntryDTO>
 */
final class ProtocolTemplateEntryResource extends AbstractResource
{
    protected string $endpoint = 'v1/protocolTemplateEntry';
    protected string $dtoClass = ProtocolTemplateEntryDTO::class;
    protected string $listKey  = 'protocolTemplateEntry';

    public function release(int $id): void
    {
        $this->http->put("{$this->endpoint}/{$id}/release", []);
    }

    public function revision(int $id): void
    {
        $this->http->put("{$this->endpoint}/{$id}/revision", []);
    }
}
