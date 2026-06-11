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
        // rawurlencode, not urlencode: in a PATH segment a space must become %20.
        // urlencode produces '+', which the server reads as a literal plus sign —
        // a search for "a b" would silently search for "a+b".
        return SearchDTO::fromArray($this->http->get('search/' . rawurlencode($query)));
    }
}
