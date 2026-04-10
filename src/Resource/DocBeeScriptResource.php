<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DocBeeScriptDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee DocBeeScript records.
 *
 * @extends AbstractResource<DocBeeScriptDTO>
 */
final class DocBeeScriptResource extends AbstractResource
{
    protected string $endpoint = 'v1/docBeeScript';
    protected string $dtoClass = DocBeeScriptDTO::class;
    protected string $listKey  = 'docBeeScript';
}