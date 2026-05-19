<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TagDTO;
use miralsoft\docbee\api\Exception\NotFoundException;

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
     * The Docbee API does not honour `name-eq=` on this endpoint — it is silently
     * ignored and returns the unfiltered list.  This method performs a cursor scan
     * (typically 1 page for tenants with ≤ 100 tags) and applies an exact
     * client-side match.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): TagDTO
    {
        foreach ($this->cursor() as $tag) {
            if ($tag->getName() === $name) {
                return $tag;
            }
        }

        throw new NotFoundException(
            message:    "Tag with name '{$name}' not found.",
            statusCode: 404,
            requestUrl: $this->endpoint,
        );
    }

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}
