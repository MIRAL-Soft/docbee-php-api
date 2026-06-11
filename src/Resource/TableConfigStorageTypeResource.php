<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/** Provides access to Docbee table config storage types. */
final class TableConfigStorageTypeResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /** @throws \InvalidArgumentException when $type is empty. */
    public function get(string $type): array
    {
        if (trim($type) === '') {
            throw new \InvalidArgumentException('get(): type must not be empty.');
        }
        return $this->http->get('tableConfigStorageType/' . rawurlencode($type));
    }
}
