<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\SystemStatusDTO;

/** Provides access to the Docbee system status. */
final class SystemStatusResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function get(): SystemStatusDTO
    {
        return SystemStatusDTO::fromArray($this->http->get('v1/systemStatus'));
    }
}
