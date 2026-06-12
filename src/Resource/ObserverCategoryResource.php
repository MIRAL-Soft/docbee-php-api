<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ObserverCategoryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee ObserverCategory records.
 *
 * @extends AbstractResource<ObserverCategoryDTO>
 */
final class ObserverCategoryResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'observerCategory';
    protected string $dtoClass = ObserverCategoryDTO::class;
    protected string $listKey  = 'observerCategory';

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
    /** @return array<string, mixed> */
    public function getCustomFields(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/customFields"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateCustomFields(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/customFields", $data); }
}