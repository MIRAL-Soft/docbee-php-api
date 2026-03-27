<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DocumentTaskDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to tasks within a Docbee document.
 *
 * @extends AbstractResource<DocumentTaskDTO>
 */
final class DocumentTaskResource extends AbstractResource
{
    protected string $endpoint = 'docbeedocumenttask';
    protected string $dtoClass = DocumentTaskDTO::class;
    protected string $listKey  = 'docBeeDocumentTask';

    /**
     * Returns all tasks belonging to a specific document.
     *
     * @return list<DocumentTaskDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByDocument(int $documentId): array
    {
        return $this->list(QueryBuilder::new()->filterEq('docBeeDocument', $documentId));
    }

    /**
     * Returns only completed tasks for a document.
     *
     * @return list<DocumentTaskDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findCompleted(int $documentId): array
    {
        $query = QueryBuilder::new()
            ->filterEq('docBeeDocument', $documentId)
            ->filterEq('completed', true);
        return $this->list($query);
    }
}
