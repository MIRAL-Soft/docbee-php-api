<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DocBeeScriptParameterDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee DocBeeScriptParameter records (sub-resource).
 *
 * @extends AbstractResource<DocBeeScriptParameterDTO>
 */
final class DocBeeScriptParameterResource extends AbstractResource
{
    protected string $dtoClass = DocBeeScriptParameterDTO::class;
    protected string $listKey  = 'docBeeScriptParameter';

    public function __construct(HttpClientInterface $http, int $scriptId)
    {
        $this->endpoint = "v1/docBeeScript/{$scriptId}/param";
        parent::__construct($http);
    }
}