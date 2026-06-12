<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/**
 * Provides access to Docbee Management Information System (MIS) charts.
 *
 * All methods accept an optional `$params` array of query parameters (date range,
 * filters, …) which is appended to the request — previously the parameter existed
 * but was silently discarded, so every call returned unfiltered default data.
 *
 * The `…Csv()` variants return raw CSV bytes (`string`); the chart/sum variants
 * return decoded JSON (`array`).
 */
final class MisResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /**
     * Builds the endpoint path including the optional query parameters.
     *
     * @param array<string, mixed> $params
     */
    private function path(string $endpoint, array $params): string
    {
        return $params === [] ? $endpoint : $endpoint . '?' . http_build_query($params);
    }

    /**
     * Fetches a JSON chart endpoint with optional query parameters.
     *
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    private function chart(string $endpoint, array $params): array
    {
        return $this->http->get($this->path($endpoint, $params));
    }

    /**
     * Fetches a CSV chart endpoint with optional query parameters (raw bytes).
     *
     * @param array<string, mixed> $params
     */
    private function chartCsv(string $endpoint, array $params): string
    {
        return $this->http->getRaw($this->path($endpoint, $params));
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getAgreementChart(array $params = []): array { return $this->chart('mis/agreementChart', $params); }
    /** @param array<string, mixed> $params */
    public function getAgreementChartCsv(array $params = []): string { return $this->chartCsv('mis/agreementChartCsv', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getAgreementChartSum(array $params = []): array { return $this->chart('mis/agreementChartSum', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getContingentChart(array $params = []): array { return $this->chart('mis/contingentChart', $params); }
    /** @param array<string, mixed> $params */
    public function getContingentChartCsv(array $params = []): string { return $this->chartCsv('mis/contingentChartCsv', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getContingentChartSum(array $params = []): array { return $this->chart('mis/contingentChartSum', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getCustomerChart(array $params = []): array { return $this->chart('mis/customerChart', $params); }
    /** @param array<string, mixed> $params */
    public function getCustomerChartCsv(array $params = []): string { return $this->chartCsv('mis/customerChartCsv', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getCustomerChartSum(array $params = []): array { return $this->chart('mis/customerChartSum', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getCustomerDailyChart(array $params = []): array { return $this->chart('mis/customerDailyChart', $params); }
    /** @param array<string, mixed> $params */
    public function getCustomerDailyChartCsv(array $params = []): string { return $this->chartCsv('mis/customerDailyChartCsv', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getCustomerDailyChartSum(array $params = []): array { return $this->chart('mis/customerDailyChartSum', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getProtocolChart(array $params = []): array { return $this->chart('mis/protocolChart', $params); }
    /** @param array<string, mixed> $params */
    public function getProtocolChartCsv(array $params = []): string { return $this->chartCsv('mis/protocolChartCsv', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getProtocolChartSum(array $params = []): array { return $this->chart('mis/protocolChartSum', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getServiceTypeChart(array $params = []): array { return $this->chart('mis/serviceTypeChart', $params); }
    /** @param array<string, mixed> $params */
    public function getServiceTypeChartCsv(array $params = []): string { return $this->chartCsv('mis/serviceTypeChartCsv', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getServiceTypeChartSum(array $params = []): array { return $this->chart('mis/serviceTypeChartSum', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getWorkerChart(array $params = []): array { return $this->chart('mis/workerChart', $params); }
    /** @param array<string, mixed> $params */
    public function getWorkerChartCsv(array $params = []): string { return $this->chartCsv('mis/workerChartCsv', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getWorkerChartSum(array $params = []): array { return $this->chart('mis/workerChartSum', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getWorkerDailyChart(array $params = []): array { return $this->chart('mis/workerDailyChart', $params); }
    /** @param array<string, mixed> $params */
    public function getWorkerDailyChartCsv(array $params = []): string { return $this->chartCsv('mis/workerDailyChartCsv', $params); }
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getWorkerDailyChartSum(array $params = []): array { return $this->chart('mis/workerDailyChartSum', $params); }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @deprecated
     */
    public function getRequestServiceTypeChartData(array $params = []): array { return $this->chart('mis/requestServiceTypeChartData', $params); }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @deprecated
     */
    public function getRequestWorkerChartData(array $params = []): array { return $this->chart('mis/requestWorkerChartData', $params); }
}
