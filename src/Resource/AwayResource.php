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
    protected string $endpoint = 'v1/away';
    protected string $dtoClass = AwayDTO::class;
    protected string $listKey  = 'away';

    public function export(int $exportProfileId): array { return $this->http->get("{$this->endpoint}/export/{$exportProfileId}"); }
    public function exportByIds(int $exportProfileId, array $ids): array { return $this->http->post("{$this->endpoint}/exportByIds/{$exportProfileId}", ['ids' => $ids]); }
}