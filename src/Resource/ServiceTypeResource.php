<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ServiceTypeDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee service types.
 *
 * @extends AbstractResource<ServiceTypeDTO>
 */
final class ServiceTypeResource extends AbstractResource
{
    protected string $endpoint = 'servicetype';
    protected string $dtoClass = ServiceTypeDTO::class;
    protected string $listKey  = 'serviceType';

    /**
     * Finds a service type by its exact name.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): ServiceTypeDTO
    {
        $results = $this->list(QueryBuilder::new()->filterEq('name', $name)->limit(1));
        if (empty($results)) {
            throw new NotFoundException("ServiceType with name '{$name}' not found.", 404, $this->endpoint);
        }
        return $results[0];
    }

    /**
     * Finds a service type by its number.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByNumber(string $number): ServiceTypeDTO
    {
        $results = $this->list(QueryBuilder::new()->filterEq('number', $number)->limit(1));
        if (empty($results)) {
            throw new NotFoundException("ServiceType with number '{$number}' not found.", 404, $this->endpoint);
        }
        return $results[0];
    }
}
