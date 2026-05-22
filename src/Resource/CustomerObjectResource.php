<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomerObjectDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CustomerObject records.
 *
 * @extends AbstractResource<CustomerObjectDTO>
 */
final class CustomerObjectResource extends AbstractResource
{
    protected string $endpoint = 'customerObject';
    protected string $dtoClass = CustomerObjectDTO::class;
    protected string $listKey  = 'customerObject';

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
    public function move(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/move", $data); }
    public function findByScanCode(string $scanCode): array { return $this->http->get("{$this->endpoint}/findByScanCode/{$scanCode}"); }
    public function export(int $exportProfileId): string { return $this->http->getRaw("{$this->endpoint}/export/{$exportProfileId}"); }
    public function exportByIds(int $exportProfileId, array $ids): string { return $this->http->postRaw("{$this->endpoint}/exportByIds/{$exportProfileId}", $ids); }
}