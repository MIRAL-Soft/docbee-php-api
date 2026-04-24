<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\QueueDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Queue records.
 *
 * @extends AbstractResource<QueueDTO>
 */
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

final class QueueResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'queue';
    protected string $dtoClass = QueueDTO::class;
    protected string $listKey  = 'queue';

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}