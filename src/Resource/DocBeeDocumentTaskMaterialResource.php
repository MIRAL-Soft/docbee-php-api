<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\MaterialDTO;

/**
 * Provides access to Docbee Material records for a DocBeeDocument Task (sub-resource).
 *
 * @extends AbstractResource<MaterialDTO>
 */
final class DocBeeDocumentTaskMaterialResource extends AbstractResource
{
    protected string $dtoClass = MaterialDTO::class;
    protected string $listKey  = 'material';

    public function __construct(HttpClientInterface $http, int $docBeeDocumentId, int $taskId)
    {
        $this->endpoint = "docBeeDocument/{$docBeeDocumentId}/task/{$taskId}/material";
        parent::__construct($http);
    }

    /**
     * Finds the first material entry for the given material item ID, or null if not found.
     *
     * Tasks typically have few materials, so a full list scan is acceptable here.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByMaterialItemId(int $materialItemId): ?MaterialDTO
    {
        foreach ($this->list() as $material) {
            if ($material->getMaterialItem() === $materialItemId) {
                return $material;
            }
        }

        return null;
    }

    /**
     * Adds a quantity for a material item, or increments the existing amount.
     *
     * If a material entry for `$materialItemId` already exists its `amount` is
     * increased by `$quantity`.  Otherwise a new entry is created with `$quantity`
     * as the initial amount.
     *
     * This is an idempotent read-before-write operation — safe to call multiple
     * times with the same arguments without duplicating entries.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function addOrIncrementByMaterialItemId(int $materialItemId, float $quantity): MaterialDTO
    {
        $existing = $this->findByMaterialItemId($materialItemId);

        if ($existing !== null) {
            /** @var MaterialDTO */
            return $this->update($existing->getId(), [
                'amount' => ($existing->getAmount() ?? 0.0) + $quantity,
            ]);
        }

        /** @var MaterialDTO */
        return $this->create(['materialItem' => $materialItemId, 'amount' => $quantity]);
    }
}
