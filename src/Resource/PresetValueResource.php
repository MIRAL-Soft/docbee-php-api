<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\PresetValueDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee PresetValue records (sub-resource).
 *
 * @extends AbstractResource<PresetValueDTO>
 */
final class PresetValueResource extends AbstractResource
{
    protected string $dtoClass = PresetValueDTO::class;
    protected string $listKey  = 'presetValue';

    public function __construct(HttpClientInterface $http, int $presetProfileId)
    {
        $this->endpoint = "v1/presetProfile/{$presetProfileId}/value";
        parent::__construct($http);
    }
}