<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\SelectionValueDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee SelectionValue records (sub-resource).
 *
 * @extends AbstractResource<SelectionValueDTO>
 */
final class SelectionValueResource extends AbstractResource
{
    protected string $dtoClass = SelectionValueDTO::class;
    protected string $listKey  = 'selectionValue';

    public function __construct(HttpClientInterface $http, int $selectionCategoryId)
    {
        $this->endpoint = "selectionCategory/{$selectionCategoryId}/selectionValue";
        parent::__construct($http);
    }

    public function findByScanCode(int $selectionCategoryId, string $scanCode): array
    {
        return $this->http->get("selectionCategory/{$selectionCategoryId}/selectionValue/findByScanCode/{$scanCode}");
    }

    public function guess(int $selectionCategoryId, array $data): array
    {
        return $this->http->post("selectionCategory/{$selectionCategoryId}/selectionValue/guess", $data);
    }

    public function addFilter(int $selectionCategoryId, int $id): void
    {
        $this->http->put("selectionCategory/{$selectionCategoryId}/selectionValue/{$id}/addFilter", []);
    }

    public function removeFilter(int $selectionCategoryId, int $id): void
    {
        $this->http->put("selectionCategory/{$selectionCategoryId}/selectionValue/{$id}/removeFilter", []);
    }
}