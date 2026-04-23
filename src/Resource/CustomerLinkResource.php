<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\LinkDTO;

/**
 * Provides access to Docbee Link records (sub-resource of customer).
 *
 * @extends AbstractResource<LinkDTO>
 */
final class CustomerLinkResource extends AbstractResource
{
    protected string $dtoClass = LinkDTO::class;
    protected string $listKey  = 'link';

    public function __construct(HttpClientInterface $http, int $customerId)
    {
        $this->endpoint = "customer/{$customerId}/link";
        parent::__construct($http);
    }
}
