<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\SiteConfigDTO;

/** Provides access to the Docbee ticket site configuration. */
final class TicketSiteConfigResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function get(): SiteConfigDTO
    {
        return SiteConfigDTO::fromArray($this->http->get('ticketSiteConfig'));
    }

    public function update(array $data): SiteConfigDTO
    {
        return SiteConfigDTO::fromArray($this->http->put('ticketSiteConfig', $data));
    }
}
