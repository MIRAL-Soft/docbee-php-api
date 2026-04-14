<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ServiceProviderUserDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ServiceProviderUser records.
 *
 * @extends AbstractResource<ServiceProviderUserDTO>
 */
final class ServiceProviderUserResource extends AbstractResource
{
    protected string $endpoint = 'v1/serviceProviderUser';
    protected string $dtoClass = ServiceProviderUserDTO::class;
    protected string $listKey  = 'serviceProviderUser';

    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }
    public function reset2FA(int $id): void { $this->http->put("{$this->endpoint}/{$id}/reset2FA", []); }
}