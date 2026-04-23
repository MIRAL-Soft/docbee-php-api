<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DailyClosingConfigDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee DailyClosingConfig records.
 *
 * @extends AbstractResource<DailyClosingConfigDTO>
 */
final class DailyClosingConfigResource extends AbstractResource
{
    protected string $endpoint = 'dailyClosingConfig';
    protected string $dtoClass = DailyClosingConfigDTO::class;
    protected string $listKey  = 'dailyClosingConfig';

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}