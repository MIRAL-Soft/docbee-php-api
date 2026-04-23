<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ObjectCategoryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ObjectCategory records.
 *
 * @extends AbstractResource<ObjectCategoryDTO>
 */
final class ObjectCategoryResource extends AbstractResource
{
    protected string $endpoint = 'objectCategory';
    protected string $dtoClass = ObjectCategoryDTO::class;
    protected string $listKey  = 'objectCategory';

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }

    public function getCustomFields(int $id): array           { return $this->http->get("{$this->endpoint}/{$id}/customFields"); }
    public function updateCustomFields(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/customFields", $data); }
}