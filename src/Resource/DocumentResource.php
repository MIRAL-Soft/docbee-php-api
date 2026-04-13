<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DocBeeDocumentDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee documents (protocols / reports).
 *
 * @extends AbstractResource<DocBeeDocumentDTO>
 */
final class DocumentResource extends AbstractResource
{
    protected string $endpoint = 'docbeedocument';
    protected string $dtoClass = DocBeeDocumentDTO::class;
    protected string $listKey  = 'docBeeDocument';

    /**
     * Returns all documents for a given customer.
     *
     * @return list<DocBeeDocumentDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomer(int $customerId): array
    {
        return $this->listAll(QueryBuilder::new()->filterEq('customer', $customerId));
    }

    /**
     * Returns all documents based on a given template.
     *
     * @return list<DocBeeDocumentDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByTemplate(int $templateId): array
    {
        return $this->listAll(QueryBuilder::new()->filterEq('template', $templateId));
    }
}
