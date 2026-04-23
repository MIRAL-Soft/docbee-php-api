<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\AbstractDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTaskDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to tasks within a Docbee document.
 *
 * @extends AbstractResource<DocBeeDocumentTaskDTO>
 */
final class DocumentTaskResource extends AbstractResource
{
    protected string $endpoint = 'docBeeDocumentTask';
    protected string $dtoClass = DocBeeDocumentTaskDTO::class;
    protected string $listKey  = 'docBeeDocumentTask';

    /**
     * Creating tasks via the top-level endpoint is not supported by the Docbee API
     * (POST /docBeeDocumentTask returns HTTP 500). Tasks must be created via the
     * sub-resource endpoint POST /docBeeDocument/{id}/task.
     *
     * @throws \LogicException always — use $client->docBeeDocumentTasks(int $documentId)->create([...]) instead.
     */
    public function create(array $data): AbstractDTO
    {
        throw new \LogicException(
            'DocumentTask records cannot be created via the top-level endpoint. ' .
            'Use $client->docBeeDocumentTasks(int $documentId)->create([...]) instead, ' .
            'which posts to docBeeDocument/{id}/task.'
        );
    }

    /**
     * Returns all tasks belonging to a specific document.
     *
     * @return list<DocBeeDocumentTaskDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByDocument(int $documentId): array
    {
        return $this->list(QueryBuilder::new()->filterEq('docBeeDocument', $documentId));
    }

    /**
     * Returns only finished tasks for a document.
     *
     * @return list<DocBeeDocumentTaskDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findFinished(int $documentId): array
    {
        $query = QueryBuilder::new()
            ->filterEq('docBeeDocument', $documentId)
            ->filterEq('finished', true);
        return $this->list($query);
    }
}
