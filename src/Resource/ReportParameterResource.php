<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ReportParameterDTO;

/**
 * Provides access to Docbee ReportParameter records (sub-resource of report).
 *
 * @extends AbstractResource<ReportParameterDTO>
 */
final class ReportParameterResource extends AbstractResource
{
    protected string $dtoClass = ReportParameterDTO::class;
    protected string $listKey  = 'reportParameter';

    public function __construct(HttpClientInterface $http, int $reportId)
    {
        $this->endpoint = "report/{$reportId}/parameter";
        parent::__construct($http);
    }
}
