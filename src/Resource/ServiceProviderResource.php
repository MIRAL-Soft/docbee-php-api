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
    protected string $endpoint = 'v1/serviceProvider';
    protected string $dtoClass = ServiceProviderDTO::class;
    protected string $listKey  = 'serviceProvider';

    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}