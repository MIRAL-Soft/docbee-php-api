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
    protected string $endpoint = 'skill';
    protected string $dtoClass = SkillDTO::class;
    protected string $listKey  = 'skill';

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}