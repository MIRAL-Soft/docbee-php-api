<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ObjectDTO;

/**
 * Provides access to Docbee Object records.
 *
 * @extends AbstractResource<ObjectDTO>
 */
final class ObjectResource extends AbstractResource
{
    protected string $endpoint = 'v1/object';
    protected string $dtoClass = ObjectDTO::class;
    protected string $listKey  = 'object';

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}
