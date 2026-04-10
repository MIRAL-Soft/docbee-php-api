<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\SkillDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Skill records.
 *
 * @extends AbstractResource<SkillDTO>
 */
final class SkillResource extends AbstractResource
{
    protected string $endpoint = 'v1/skill';
    protected string $dtoClass = SkillDTO::class;
    protected string $listKey  = 'skill';
}