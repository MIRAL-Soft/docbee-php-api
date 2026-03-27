<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\PriorityDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ticket priority levels.
 *
 * @extends AbstractResource<PriorityDTO>
 */
final class PriorityResource extends AbstractResource
{
    protected string $endpoint = 'priority';
    protected string $dtoClass = PriorityDTO::class;
    protected string $listKey  = 'priority';

    /**
     * Finds a priority by its exact name.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): PriorityDTO
    {
        $results = $this->list(QueryBuilder::new()->filterEq('name', $name)->limit(1));
        if (empty($results)) {
            throw new NotFoundException("Priority with name '{$name}' not found.", 404, $this->endpoint);
        }
        return $results[0];
    }
}
