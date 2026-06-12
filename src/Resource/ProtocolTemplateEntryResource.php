<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ProtocolTemplateEntryDTO;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee ProtocolTemplateEntry records.
 *
 * @extends AbstractResource<ProtocolTemplateEntryDTO>
 */
final class ProtocolTemplateEntryResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'protocolTemplateEntry';
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
