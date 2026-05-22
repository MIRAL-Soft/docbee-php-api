<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\AwayDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Away records.
 *
 * @extends AbstractResource<AwayDTO>
 */
final class AwayResource extends AbstractResource
{
    protected string $endpoint = 'away';
    protected string $dtoClass = AwayDTO::class;
    protected string $listKey  = 'away';

    public function export(int $exportProfileId): string { return $this->http->getRaw("{$this->endpoint}/export/{$exportProfileId}"); }
    public function exportByIds(int $exportProfileId, array $ids): string { return $this->http->postRaw("{$this->endpoint}/exportByIds/{$exportProfileId}", ['ids' => $ids]); }
}