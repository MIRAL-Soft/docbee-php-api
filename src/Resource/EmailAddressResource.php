<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/** Provides access to Docbee email addresses. */
final class EmailAddressResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /** Returns a list of email addresses. */
    public function list(): array
    {
        return $this->http->get('emailAddress/list')['emailAddress'] ?? [];
    }
}
