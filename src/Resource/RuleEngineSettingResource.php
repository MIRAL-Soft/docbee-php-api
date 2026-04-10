<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\RuleEngineSettingDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee RuleEngineSetting records (sub-resource).
 *
 * @extends AbstractResource<RuleEngineSettingDTO>
 */
final class RuleEngineSettingResource extends AbstractResource
{
    protected string $dtoClass = RuleEngineSettingDTO::class;
    protected string $listKey  = 'ruleEngineSetting';

    public function __construct(HttpClientInterface $http, int $actionId)
    {
        $this->endpoint = "v1/ruleEngineAction/{$actionId}/setting";
        parent::__construct($http);
    }
}