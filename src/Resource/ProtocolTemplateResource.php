<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ProtocolTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ProtocolTemplate records.
 *
 * @extends AbstractResource<ProtocolTemplateDTO>
 */
final class ProtocolTemplateResource extends AbstractResource
{
    protected string $endpoint = 'v1/protocolTemplate';
    protected string $dtoClass = ProtocolTemplateDTO::class;
    protected string $listKey  = 'protocolTemplate';

    public function release(int $id): void { $this->http->put("{$this->endpoint}/{$id}/release", []); }
    public function revision(int $id): void { $this->http->put("{$this->endpoint}/{$id}/revision", []); }
}