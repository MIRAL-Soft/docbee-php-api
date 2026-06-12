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

    /**
     * Finds a selection value by its scan code (barcode/QR).
     *
     * The scan code is URL-encoded — scan codes come from physical labels and
     * must never be able to alter the request path or query.
     *
     * @throws \InvalidArgumentException when $scanCode is empty.
     *
     * @return array<string, mixed>
     */
    public function findByScanCode(int $selectionCategoryId, string $scanCode): array
    {
        if (trim($scanCode) === '') {
            throw new \InvalidArgumentException('findByScanCode(): scanCode must not be empty.');
        }
        return $this->http->get(
            "selectionCategory/{$selectionCategoryId}/selectionValue/findByScanCode/" . rawurlencode($scanCode)
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
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