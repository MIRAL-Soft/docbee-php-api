<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\GroupDTO;

/**
 * Provides access to Docbee Group records (sub-resource of protocolTemplate).
 *
 * @extends AbstractResource<GroupDTO>
 */
final class GroupResource extends AbstractResource
{
    protected string $dtoClass = GroupDTO::class;
    protected string $listKey  = 'group';

    public function __construct(HttpClientInterface $http, int $templateId)
    {
        $this->endpoint = "v1/protocolTemplate/{$templateId}/group";
        parent::__construct($http);
    }
}
