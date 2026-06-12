<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\SelectionCategoryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee SelectionCategory records.
 *
 * @extends AbstractResource<SelectionCategoryDTO>
 */
final class SelectionCategoryResource extends AbstractResource
{
    protected string $endpoint = 'selectionCategory';
    protected string $dtoClass = SelectionCategoryDTO::class;
    protected string $listKey  = 'selectionCategory';

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }

    /**
     * @return array<string, mixed>
     */
    public function getCustomFields(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/customFields"); }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateCustomFields(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/customFields", $data); }
}