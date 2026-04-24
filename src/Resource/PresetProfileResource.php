<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\PresetProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee PresetProfile records.
 *
 * @extends AbstractResource<PresetProfileDTO>
 */
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

final class PresetProfileResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'presetProfile';
    protected string $dtoClass = PresetProfileDTO::class;
    protected string $listKey  = 'presetProfile';
}