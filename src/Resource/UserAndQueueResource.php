<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\UserAndQueueDTO;

/**
 * Provides access to Docbee UserAndQueue records.
 *
 * @extends AbstractResource<UserAndQueueDTO>
 */
final class UserAndQueueResource extends AbstractResource
{
    protected string $endpoint = 'userAndQueue';
    protected string $dtoClass = UserAndQueueDTO::class;
    protected string $listKey  = 'userAndQueue';

    public function guess(array $data): array
    {
        return $this->http->post("{$this->endpoint}/guess", $data);
    }
}
