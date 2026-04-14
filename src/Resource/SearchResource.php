<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\SearchDTO;

/** Provides access to the Docbee global search. */
final class SearchResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function search(string $query): SearchDTO
    {
        return SearchDTO::fromArray($this->http->get('v1/search/' . urlencode($query)));
    }
}
