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

    /** Builds the endpoint path including the optional query parameters. */
    private function path(string $endpoint, array $params): string
    {
        return $params === [] ? $endpoint : $endpoint . '?' . http_build_query($params);
    }

    /** Fetches a JSON chart endpoint with optional query parameters. */
    private function chart(string $endpoint, array $params): array
    {
        return $this->http->get($this->path($endpoint, $params));
    }

    /** Fetches a CSV chart endpoint with optional query parameters (raw bytes). */
    private function chartCsv(string $endpoint, array $params): string
    {
        return $this->http->getRaw($this->path($endpoint, $params));
    }

    public function getAgreementChart(array $params = []): array { return $this->chart('mis/agreementChart', $params); }
    public function getAgreementChartCsv(array $params = []): string { return $this->chartCsv('mis/agreementChartCsv', $params); }
    public function getAgreementChartSum(array $params = []): array { return $this->chart('mis/agreementChartSum', $params); }
    public function getContingentChart(array $params = []): array { return $this->chart('mis/contingentChart', $params); }
    public function getContingentChartCsv(array $params = []): string { return $this->chartCsv('mis/contingentChartCsv', $params); }
    public function getContingentChartSum(array $params = []): array { return $this->chart('mis/contingentChartSum', $params); }
    public function getCustomerChart(array $params = []): array { return $this->chart('mis/customerChart', $params); }
    public function getCustomerChartCsv(array $params = []): string { return $this->chartCsv('mis/customerChartCsv', $params); }
    public function getCustomerChartSum(array $params = []): array { return $this->chart('mis/customerChartSum', $params); }
    public function getCustomerDailyChart(array $params = []): array { return $this->chart('mis/customerDailyChart', $params); }
    public function getCustomerDailyChartCsv(array $params = []): string { return $this->chartCsv('mis/customerDailyChartCsv', $params); }
    public function getCustomerDailyChartSum(array $params = []): array { return $this->chart('mis/customerDailyChartSum', $params); }
    public function getProtocolChart(array $params = []): array { return $this->chart('mis/protocolChart', $params); }
    public function getProtocolChartCsv(array $params = []): string { return $this->chartCsv('mis/protocolChartCsv', $params); }
    public function getProtocolChartSum(array $params = []): array { return $this->chart('mis/protocolChartSum', $params); }
    public function getServiceTypeChart(array $params = []): array { return $this->chart('mis/serviceTypeChart', $params); }
    public function getServiceTypeChartCsv(array $params = []): string { return $this->chartCsv('mis/serviceTypeChartCsv', $params); }
    public function getServiceTypeChartSum(array $params = []): array { return $this->chart('mis/serviceTypeChartSum', $params); }
    public function getWorkerChart(array $params = []): array { return $this->chart('mis/workerChart', $params); }
    public function getWorkerChartCsv(array $params = []): string { return $this->chartCsv('mis/workerChartCsv', $params); }
    public function getWorkerChartSum(array $params = []): array { return $this->chart('mis/workerChartSum', $params); }
    public function getWorkerDailyChart(array $params = []): array { return $this->chart('mis/workerDailyChart', $params); }
    public function getWorkerDailyChartCsv(array $params = []): string { return $this->chartCsv('mis/workerDailyChartCsv', $params); }
    public function getWorkerDailyChartSum(array $params = []): array { return $this->chart('mis/workerDailyChartSum', $params); }

    /** @deprecated */
    public function getRequestServiceTypeChartData(array $params = []): array { return $this->chart('mis/requestServiceTypeChartData', $params); }

    /** @deprecated */
    public function getRequestWorkerChartData(array $params = []): array { return $this->chart('mis/requestWorkerChartData', $params); }
}
