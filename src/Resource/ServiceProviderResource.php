<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ServiceProviderDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ServiceProvider records.
 *
 * @extends AbstractResource<ServiceProviderDTO>
 */
final class ServiceProviderResource extends AbstractResource
{
    protected string $endpoint = 'serviceProvider';
    protected string $dtoClass = ServiceProviderDTO::class;
    protected string $listKey  = 'serviceProvider';

    /**
     * @return array<string, mixed>
     */
    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}