<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DocBeeDocumentConflictDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee DocBeeDocumentConflict records (sub-resource).
 *
 * @extends AbstractResource<DocBeeDocumentConflictDTO>
 */
final class DocBeeDocumentConflictResource extends AbstractResource
{
    protected string $dtoClass = DocBeeDocumentConflictDTO::class;
    protected string $listKey  = 'docBeeDocumentConflict';

    public function __construct(HttpClientInterface $http, int $documentId)
    {
        $this->endpoint = "v1/docBeeDocument/{$documentId}/conflict";
        parent::__construct($http);
    }
}