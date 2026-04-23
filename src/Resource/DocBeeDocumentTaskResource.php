<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DocBeeDocumentTaskDTO;

/**
 * Provides access to Docbee Task records for a DocBeeDocument (sub-resource).
 *
 * @extends AbstractResource<DocBeeDocumentTaskDTO>
 */
final class DocBeeDocumentTaskResource extends AbstractResource
{
    protected string $dtoClass = DocBeeDocumentTaskDTO::class;
    protected string $listKey  = 'task';

    public function __construct(HttpClientInterface $http, int $docBeeDocumentId)
    {
        $this->endpoint = "docBeeDocument/{$docBeeDocumentId}/task";
        parent::__construct($http);
    }
}
