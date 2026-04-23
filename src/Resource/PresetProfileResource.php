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
final class PresetProfileResource extends AbstractResource
{
    protected string $endpoint = 'presetProfile';
    protected string $dtoClass = PresetProfileDTO::class;
    protected string $listKey  = 'presetProfile';
}