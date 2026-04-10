<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\RuleEngineConditionDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee RuleEngineCondition records (sub-resource).
 *
 * @extends AbstractResource<RuleEngineConditionDTO>
 */
final class RuleEngineConditionResource extends AbstractResource
{
    protected string $dtoClass = RuleEngineConditionDTO::class;
    protected string $listKey  = 'ruleEngineCondition';

    public function __construct(HttpClientInterface $http, int $actionId)
    {
        $this->endpoint = "v1/ruleEngineAction/{$actionId}/condition";
        parent::__construct($http);
    }
}