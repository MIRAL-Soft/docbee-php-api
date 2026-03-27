<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DocumentDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee documents (protocols / reports).
 *
 * @extends AbstractResource<DocumentDTO>
 */
final class DocumentResource extends AbstractResource
{
    protected string $endpoint = 'docbeedocument';
    protected string $dtoClass = DocumentDTO::class;
    protected string $listKey  = 'docBeeDocument';

    /**
     * Returns all documents for a given customer.
     *
     * @return list<DocumentDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomer(int $customerId): array
    {
        return $this->listAll(QueryBuilder::new()->filterEq('customer', $customerId));
    }

    /**
     * Returns all documents based on a given template.
     *
     * @return list<DocumentDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByTemplate(int $templateId): array
    {
        return $this->list(QueryBuilder::new()->filterEq('template', $templateId));
    }
}
