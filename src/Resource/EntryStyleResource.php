<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\EntryStyleDTO;

/**
 * Provides access to Docbee EntryStyle records (sub-resource of protocolTemplate).
 *
 * @extends AbstractResource<EntryStyleDTO>
 */
final class EntryStyleResource extends AbstractResource
{
    protected string $dtoClass = EntryStyleDTO::class;
    protected string $listKey  = 'entryStyle';

    public function __construct(HttpClientInterface $http, int $templateId)
    {
        $this->endpoint = "protocolTemplate/{$templateId}/entryStyle";
        parent::__construct($http);
    }
}
