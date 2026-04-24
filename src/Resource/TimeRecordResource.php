<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TimeRecordDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TimeRecord records.
 *
 * @extends AbstractResource<TimeRecordDTO>
 */
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

final class TimeRecordResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'timeRecord';
    protected string $dtoClass = TimeRecordDTO::class;
    protected string $listKey  = 'timeRecord';

    /** Returns the current active time record. */
    public function getCurrent(): ?TimeRecordDTO
    {
        $data = $this->http->get("{$this->endpoint}/current");
        return empty($data) ? null : TimeRecordDTO::fromArray($data);
    }

    /** Start a new time record. */
    public function start(array $data = []): TimeRecordDTO
    {
        return TimeRecordDTO::fromArray($this->http->post("{$this->endpoint}/start", $data));
    }

    /** Stop the current time record. */
    public function stop(array $data = []): TimeRecordDTO
    {
        return TimeRecordDTO::fromArray($this->http->put("{$this->endpoint}/stop", $data));
    }
}