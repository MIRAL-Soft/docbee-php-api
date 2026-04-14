<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\MaterialDTO;

/**
 * Provides access to Docbee MaterialTemplate records (sub-resource of taskTemplate).
 *
 * @extends AbstractResource<MaterialDTO>
 */
final class TaskTemplateMaterialTemplateResource extends AbstractResource
{
    protected string $dtoClass = MaterialDTO::class;
    protected string $listKey  = 'materialTemplate';

    public function __construct(HttpClientInterface $http, int $taskTemplateId)
    {
        $this->endpoint = "v1/taskTemplate/{$taskTemplateId}/materialTemplate";
        parent::__construct($http);
    }
}
