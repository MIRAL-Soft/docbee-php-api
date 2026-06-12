<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\PriorityDTO;
use miralsoft\docbee\api\Exception\NotFoundException;

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
     * The Docbee API does not honour `name-eq=` on this endpoint — it is silently
     * ignored and returns the unfiltered list.  This method performs a cursor scan
     * (typically 1 page for tenants with ≤ 100 priorities) and applies an exact
     * client-side match.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): PriorityDTO
    {
        foreach ($this->cursor() as $priority) {
            if ($priority->getName() === $name) {
                return $priority;
            }
        }

        throw new NotFoundException(
            message:    "Priority with name '{$name}' not found.",
            statusCode: 404,
            requestUrl: $this->endpoint,
        );
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}
