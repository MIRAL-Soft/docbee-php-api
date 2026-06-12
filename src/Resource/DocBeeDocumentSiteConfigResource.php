<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\SiteConfigDTO;

/** Provides access to the Docbee document site configuration. */
final class DocBeeDocumentSiteConfigResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function get(): SiteConfigDTO
    {
        return SiteConfigDTO::fromArray($this->http->get('docBeeDocumentSiteConfig'));
    }

    /** @param array<string, mixed> $data */
    public function update(array $data): SiteConfigDTO
    {
        return SiteConfigDTO::fromArray($this->http->put('docBeeDocumentSiteConfig', $data));
    }
}
