<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\NoteDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee Note records.
 *
 * @extends AbstractResource<NoteDTO>
 */
final class NoteResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'note';
    protected string $dtoClass = NoteDTO::class;
    protected string $listKey  = 'note';
}