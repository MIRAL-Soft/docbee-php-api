<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\RequestTypeDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee request types.
 *
 * @extends AbstractResource<RequestTypeDTO>
 */
final class RequestTypeResource extends AbstractResource
{
    protected string $endpoint = 'requesttype';
    protected string $dtoClass = RequestTypeDTO::class;
    protected string $listKey  = 'requestType';

    /**
     * Finds a request type by its exact name.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): RequestTypeDTO
    {
        $results = $this->list(QueryBuilder::new()->filterEq('name', $name)->limit(1));
        if (empty($results)) {
            throw new NotFoundException("RequestType with name '{$name}' not found.", 404);
        }
        return $results[0];
    }
}
