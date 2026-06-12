<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/** Provides access to Docbee mobile numbers. */
final class MobileNumberResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /**
     * Returns a list of mobile numbers.
     *
     * @return array<int, mixed>
     */
    public function list(): array
    {
        return $this->http->get('mobileNumber/list')['mobileNumber'] ?? [];
    }
}
