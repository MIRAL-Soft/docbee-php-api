<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ReportElementDTO;

/**
 * Provides access to Docbee ReportElement records (sub-resource of report).
 *
 * @extends AbstractResource<ReportElementDTO>
 */
final class ReportElementResource extends AbstractResource
{
    protected string $dtoClass = ReportElementDTO::class;
    protected string $listKey  = 'reportElement';

    public function __construct(HttpClientInterface $http, int $reportId)
    {
        $this->endpoint = "report/{$reportId}/element";
        parent::__construct($http);
    }

    /**
     * Returns the requestValues for a given report element.
     *
     * @return array<string, mixed>
     */
    public function getRequestValues(int $elementId): array
    {
        return $this->http->get("{$this->endpoint}/{$elementId}/requestValues");
    }
}
