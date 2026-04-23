<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\NoteDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Note records.
 *
 * @extends AbstractResource<NoteDTO>
 */
final class NoteResource extends AbstractResource
{
    protected string $endpoint = 'note';
    protected string $dtoClass = NoteDTO::class;
    protected string $listKey  = 'note';
}