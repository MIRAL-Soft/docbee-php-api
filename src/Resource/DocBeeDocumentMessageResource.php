<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DocBeeDocumentMessageDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee DocBeeDocumentMessage records (sub-resource).
 *
 * @extends AbstractResource<DocBeeDocumentMessageDTO>
 */
final class DocBeeDocumentMessageResource extends AbstractResource
{
    protected string $dtoClass = DocBeeDocumentMessageDTO::class;
    protected string $listKey  = 'docBeeDocumentMessage';

    public function __construct(HttpClientInterface $http, int $documentId)
    {
        $this->endpoint = "docBeeDocument/{$documentId}/message";
        parent::__construct($http);
    }
}