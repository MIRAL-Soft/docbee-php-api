<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\RuleEngineReactionDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee RuleEngineReaction records (sub-resource).
 *
 * @extends AbstractResource<RuleEngineReactionDTO>
 */
final class RuleEngineReactionResource extends AbstractResource
{
    protected string $dtoClass = RuleEngineReactionDTO::class;
    protected string $listKey  = 'ruleEngineReaction';

    public function __construct(HttpClientInterface $http, int $actionId)
    {
        $this->endpoint = "v1/ruleEngineAction/{$actionId}/reaction";
        parent::__construct($http);
    }
}