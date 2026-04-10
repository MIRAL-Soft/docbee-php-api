<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\SlaProfileWorkingHourDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee SlaProfileWorkingHour records (sub-resource).
 *
 * @extends AbstractResource<SlaProfileWorkingHourDTO>
 */
final class SlaProfileWorkingHourResource extends AbstractResource
{
    protected string $dtoClass = SlaProfileWorkingHourDTO::class;
    protected string $listKey  = 'slaProfileWorkingHour';

    public function __construct(HttpClientInterface $http, int $slaProfileId)
    {
        $this->endpoint = "v1/slaProfile/{$slaProfileId}/workingHour";
        parent::__construct($http);
    }
}