<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TagDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee tags.
 *
 * @extends AbstractResource<TagDTO>
 */
final class TagResource extends AbstractResource
{
    protected string $endpoint = 'tag';
    protected string $dtoClass = TagDTO::class;
    protected string $listKey  = 'tag';

    /**
     * Finds a tag by its exact name.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): TagDTO
    {
        $results = $this->list(QueryBuilder::new()->filterEq('name', $name)->limit(1));
        if (empty($results)) {
            throw new NotFoundException(
                message:    "Tag with name '{$name}' not found.",
                statusCode: 404,
                requestUrl: $this->endpoint,
            );
        }
        return $results[0];
    }
}
