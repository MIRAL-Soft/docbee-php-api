<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DocBeeDocumentRecurrenceDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee DocBeeDocumentRecurrence records.
 *
 * @extends AbstractResource<DocBeeDocumentRecurrenceDTO>
 */
final class DocBeeDocumentRecurrenceResource extends AbstractResource
{
    protected string $endpoint = 'v1/docBeeDocumentRecurrence';
    protected string $dtoClass = DocBeeDocumentRecurrenceDTO::class;
    protected string $listKey  = 'docBeeDocumentRecurrence';

    public function clone(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/clone", []); }
    public function createPastDocuments(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/createPastDocuments", []); }
}