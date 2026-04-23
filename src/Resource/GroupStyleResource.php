<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\GroupStyleDTO;

/**
 * Provides access to Docbee GroupStyle records (sub-resource of protocolTemplate).
 *
 * @extends AbstractResource<GroupStyleDTO>
 */
final class GroupStyleResource extends AbstractResource
{
    protected string $dtoClass = GroupStyleDTO::class;
    protected string $listKey  = 'groupStyle';

    public function __construct(HttpClientInterface $http, int $templateId)
    {
        $this->endpoint = "protocolTemplate/{$templateId}/groupStyle";
        parent::__construct($http);
    }
}
