<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/** Provides access to Docbee table config storage settings. */
final class TableConfigStorageSettingResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /** @throws \InvalidArgumentException when $type is empty. */
    public function get(string $type): array
    {
        if (trim($type) === '') {
            throw new \InvalidArgumentException('get(): type must not be empty.');
        }
        return $this->http->get('tableConfigStorageSetting/' . rawurlencode($type));
    }

    /** @throws \InvalidArgumentException when $type is empty. */
    public function getForStorage(string $type, int $storageId): array
    {
        if (trim($type) === '') {
            throw new \InvalidArgumentException('getForStorage(): type must not be empty.');
        }
        return $this->http->get('tableConfigStorageSetting/' . rawurlencode($type) . "/{$storageId}");
    }
}
