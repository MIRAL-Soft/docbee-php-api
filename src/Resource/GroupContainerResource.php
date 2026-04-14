<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\GroupContainerDTO;

/**
 * Provides access to Docbee GroupContainer records (sub-resource of protocolTemplate).
 *
 * @extends AbstractResource<GroupContainerDTO>
 */
final class GroupContainerResource extends AbstractResource
{
    protected string $dtoClass = GroupContainerDTO::class;
    protected string $listKey  = 'groupContainer';

    public function __construct(HttpClientInterface $http, int $templateId)
    {
        $this->endpoint = "v1/protocolTemplate/{$templateId}/groupContainer";
        parent::__construct($http);
    }
}
