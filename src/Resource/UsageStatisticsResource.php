<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\UsageStatisticDTO;

/** Provides access to Docbee usage statistics. */
final class UsageStatisticsResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function get(): UsageStatisticDTO
    {
        return UsageStatisticDTO::fromArray($this->http->get('usageStatistics'));
    }

    public function update(array $data): UsageStatisticDTO
    {
        return UsageStatisticDTO::fromArray($this->http->put('usageStatistics', $data));
    }

    public function getStatistic(): array
    {
        return $this->http->get('usageStatistics/statistic');
    }
}
