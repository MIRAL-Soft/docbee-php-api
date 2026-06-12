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

    /**
     * @param array<string, mixed> $data
     */
    public function update(array $data): UsageStatisticDTO
    {
        return UsageStatisticDTO::fromArray($this->http->put('usageStatistics', $data));
    }

    /**
     * @return array<string, mixed>
     */
    public function getStatistic(): array
    {
        return $this->http->get('usageStatistics/statistic');
    }
}
