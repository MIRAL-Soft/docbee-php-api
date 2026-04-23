<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/** Provides access to Docbee table config storage types. */
final class TableConfigStorageTypeResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function get(string $type): array
    {
        return $this->http->get("tableConfigStorageType/{$type}");
    }
}
