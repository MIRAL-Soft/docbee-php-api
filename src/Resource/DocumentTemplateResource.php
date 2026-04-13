<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DocBeeDocumentTemplateDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee document templates.
 *
 * @extends AbstractResource<DocBeeDocumentTemplateDTO>
 */
final class DocumentTemplateResource extends AbstractResource
{
    protected string $endpoint = 'docbeedocumenttemplate';
    protected string $dtoClass = DocBeeDocumentTemplateDTO::class;
    protected string $listKey  = 'docBeeDocumentTemplate';

    /**
     * Finds a template by its exact name.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): DocBeeDocumentTemplateDTO
    {
        $results = $this->list(QueryBuilder::new()->filterEq('name', $name)->limit(1));
        if (empty($results)) {
            throw new NotFoundException(
                message:    "DocumentTemplate with name '{$name}' not found.",
                statusCode: 404,
                requestUrl: $this->endpoint,
            );
        }
        return $results[0];
    }
}
