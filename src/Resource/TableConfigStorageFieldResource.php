<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TableConfigStorageFieldDTO;

/**
 * Provides access to Docbee TableConfigStorageField records (sub-resource of tableConfigStorage).
 *
 * @extends AbstractResource<TableConfigStorageFieldDTO>
 */
final class TableConfigStorageFieldResource extends AbstractResource
{
    protected string $dtoClass = TableConfigStorageFieldDTO::class;
    protected string $listKey  = 'tableConfigStorageField';

    public function __construct(HttpClientInterface $http, int $storageId)
    {
        $this->endpoint = "v1/tableConfigStorage/{$storageId}/field";
        parent::__construct($http);
    }
}
