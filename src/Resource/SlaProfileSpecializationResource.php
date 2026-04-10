<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\SlaProfileSpecializationDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee SlaProfileSpecialization records (sub-resource).
 *
 * @extends AbstractResource<SlaProfileSpecializationDTO>
 */
final class SlaProfileSpecializationResource extends AbstractResource
{
    protected string $dtoClass = SlaProfileSpecializationDTO::class;
    protected string $listKey  = 'slaProfileSpecialization';

    public function __construct(HttpClientInterface $http, int $slaProfileId)
    {
        $this->endpoint = "v1/slaProfile/{$slaProfileId}/specialization";
        parent::__construct($http);
    }
}