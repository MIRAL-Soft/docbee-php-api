<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\SiteConfigDTO;

/** Provides access to the Docbee scheduler site configuration. */
final class SchedulerSiteConfigResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function get(): SiteConfigDTO
    {
        return SiteConfigDTO::fromArray($this->http->get('schedulerSiteConfig'));
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(array $data): SiteConfigDTO
    {
        return SiteConfigDTO::fromArray($this->http->put('schedulerSiteConfig', $data));
    }
}
