<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/** Provides access to Docbee Management Information System (MIS) charts. */
final class MisResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function getAgreementChart(array $params = []): array { return $this->http->get('v1/mis/agreementChart'); }
    public function getAgreementChartCsv(array $params = []): array { return $this->http->get('v1/mis/agreementChartCsv'); }
    public function getAgreementChartSum(array $params = []): array { return $this->http->get('v1/mis/agreementChartSum'); }
    public function getContingentChart(array $params = []): array { return $this->http->get('v1/mis/contingentChart'); }
    public function getContingentChartCsv(array $params = []): array { return $this->http->get('v1/mis/contingentChartCsv'); }
    public function getContingentChartSum(array $params = []): array { return $this->http->get('v1/mis/contingentChartSum'); }
    public function getCustomerChart(array $params = []): array { return $this->http->get('v1/mis/customerChart'); }
    public function getCustomerChartCsv(array $params = []): array { return $this->http->get('v1/mis/customerChartCsv'); }
    public function getCustomerChartSum(array $params = []): array { return $this->http->get('v1/mis/customerChartSum'); }
    public function getCustomerDailyChart(array $params = []): array { return $this->http->get('v1/mis/customerDailyChart'); }
    public function getCustomerDailyChartCsv(array $params = []): array { return $this->http->get('v1/mis/customerDailyChartCsv'); }
    public function getCustomerDailyChartSum(array $params = []): array { return $this->http->get('v1/mis/customerDailyChartSum'); }
    public function getProtocolChart(array $params = []): array { return $this->http->get('v1/mis/protocolChart'); }
    public function getProtocolChartCsv(array $params = []): array { return $this->http->get('v1/mis/protocolChartCsv'); }
    public function getProtocolChartSum(array $params = []): array { return $this->http->get('v1/mis/protocolChartSum'); }
    public function getServiceTypeChart(array $params = []): array { return $this->http->get('v1/mis/serviceTypeChart'); }
    public function getServiceTypeChartCsv(array $params = []): array { return $this->http->get('v1/mis/serviceTypeChartCsv'); }
    public function getServiceTypeChartSum(array $params = []): array { return $this->http->get('v1/mis/serviceTypeChartSum'); }
    public function getWorkerChart(array $params = []): array { return $this->http->get('v1/mis/workerChart'); }
    public function getWorkerChartCsv(array $params = []): array { return $this->http->get('v1/mis/workerChartCsv'); }
    public function getWorkerChartSum(array $params = []): array { return $this->http->get('v1/mis/workerChartSum'); }
    public function getWorkerDailyChart(array $params = []): array { return $this->http->get('v1/mis/workerDailyChart'); }
    public function getWorkerDailyChartCsv(array $params = []): array { return $this->http->get('v1/mis/workerDailyChartCsv'); }
    public function getWorkerDailyChartSum(array $params = []): array { return $this->http->get('v1/mis/workerDailyChartSum'); }

    /** @deprecated */
    public function getRequestServiceTypeChartData(array $params = []): array { return $this->http->get('v1/mis/requestServiceTypeChartData'); }

    /** @deprecated */
    public function getRequestWorkerChartData(array $params = []): array { return $this->http->get('v1/mis/requestWorkerChartData'); }
}
