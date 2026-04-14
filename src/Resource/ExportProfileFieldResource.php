<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ExportProfileFieldDTO;

/**
 * Provides access to Docbee ExportProfileField records (sub-resource of exportProfile).
 *
 * @extends AbstractResource<ExportProfileFieldDTO>
 */
final class ExportProfileFieldResource extends AbstractResource
{
    protected string $dtoClass = ExportProfileFieldDTO::class;
    protected string $listKey  = 'exportProfileField';

    public function __construct(HttpClientInterface $http, int $exportProfileId)
    {
        $this->endpoint = "v1/exportProfile/{$exportProfileId}/field";
        parent::__construct($http);
    }
}
