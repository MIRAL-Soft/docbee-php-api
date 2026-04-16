<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\MaterialDTO;

/**
 * Provides access to Docbee Material records for a DocBeeDocument Task (sub-resource).
 *
 * @extends AbstractResource<MaterialDTO>
 */
final class DocBeeDocumentTaskMaterialResource extends AbstractResource
{
    protected string $dtoClass = MaterialDTO::class;
    protected string $listKey  = 'material';

    public function __construct(HttpClientInterface $http, int $docBeeDocumentId, int $taskId)
    {
        $this->endpoint = "v1/docBeeDocument/{$docBeeDocumentId}/task/{$taskId}/material";
        parent::__construct($http);
    }
}
