<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\RuleEngineActionDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee RuleEngineAction records.
 *
 * @extends AbstractResource<RuleEngineActionDTO>
 */
final class RuleEngineActionResource extends AbstractResource
{
    protected string $endpoint = 'v1/ruleEngineAction';
    protected string $dtoClass = RuleEngineActionDTO::class;
    protected string $listKey  = 'ruleEngineAction';
}