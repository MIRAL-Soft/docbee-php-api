<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\CustomerSettingsDTO;

/** Provides access to Docbee customer settings. */
final class CustomerSettingsResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function get(): CustomerSettingsDTO
    {
        return CustomerSettingsDTO::fromArray($this->http->get('v1/customerSettings'));
    }

    public function update(array $data): CustomerSettingsDTO
    {
        return CustomerSettingsDTO::fromArray($this->http->put('v1/customerSettings', $data));
    }
}
