<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ErrorLogDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ErrorLog records.
 *
 * @extends AbstractResource<ErrorLogDTO>
 */
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

final class ErrorLogResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'errorLog';
    protected string $dtoClass = ErrorLogDTO::class;
    protected string $listKey  = 'errorLog';

    public function process(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/process", []); }
    public function retry(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/retry", []); }
}