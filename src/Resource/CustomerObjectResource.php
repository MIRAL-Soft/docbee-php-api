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
    /**
     * Finds a customer object by its scan code (barcode/QR).
     *
     * The scan code is URL-encoded — scan codes come from physical labels and
     * must never be able to alter the request path or query.
     *
     * @throws \InvalidArgumentException when $scanCode is empty.
     */
    public function findByScanCode(string $scanCode): array
    {
        if (trim($scanCode) === '') {
            throw new \InvalidArgumentException('findByScanCode(): scanCode must not be empty.');
        }
        return $this->http->get("{$this->endpoint}/findByScanCode/" . rawurlencode($scanCode));
    }
    public function export(int $exportProfileId): string { return $this->http->getRaw("{$this->endpoint}/export/{$exportProfileId}"); }
    public function exportByIds(int $exportProfileId, array $ids): string { return $this->http->postRaw("{$this->endpoint}/exportByIds/{$exportProfileId}", $ids); }
}