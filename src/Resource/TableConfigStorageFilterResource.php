<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TableConfigStorageFilterDTO;

/**
 * Provides access to Docbee TableConfigStorageFilter records (sub-resource of tableConfigStorage).
 *
 * @extends AbstractResource<TableConfigStorageFilterDTO>
 */
final class TableConfigStorageFilterResource extends AbstractResource
{
    protected string $dtoClass = TableConfigStorageFilterDTO::class;
    protected string $listKey  = 'tableConfigStorageFilter';

    public function __construct(HttpClientInterface $http, int $storageId)
    {
        $this->endpoint = "tableConfigStorage/{$storageId}/filter";
        parent::__construct($http);
    }
}
