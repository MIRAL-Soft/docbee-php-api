<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/** Provides access to Docbee table config storage settings. */
final class TableConfigStorageSettingResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function get(string $type): array
    {
        return $this->http->get("v1/tableConfigStorageSetting/{$type}");
    }

    public function getForStorage(string $type, int $storageId): array
    {
        return $this->http->get("v1/tableConfigStorageSetting/{$type}/{$storageId}");
    }
}
