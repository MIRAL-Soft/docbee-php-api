<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ObserverCategoryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ObserverCategory records.
 *
 * @extends AbstractResource<ObserverCategoryDTO>
 */
final class ObserverCategoryResource extends AbstractResource
{
    protected string $endpoint = 'observerCategory';
    protected string $dtoClass = ObserverCategoryDTO::class;
    protected string $listKey  = 'observerCategory';

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
    public function getCustomFields(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/customFields"); }
    public function updateCustomFields(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/customFields", $data); }
}