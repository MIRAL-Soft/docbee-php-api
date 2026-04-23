<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TimerDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Timer records.
 *
 * @extends AbstractResource<TimerDTO>
 */
final class TimerResource extends AbstractResource
{
    protected string $endpoint = 'timer';
    protected string $dtoClass = TimerDTO::class;
    protected string $listKey  = 'timer';

    /** Start a new timer. */
    public function start(array $data = []): TimerDTO
    {
        return TimerDTO::fromArray($this->http->post("{$this->endpoint}/start", $data));
    }

    /** Pause a running timer. */
    public function pause(int $id): TimerDTO
    {
        return TimerDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/pause", []));
    }

    /** Resume a paused timer. */
    public function resume(int $id): TimerDTO
    {
        return TimerDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/resume", []));
    }

    /** Stop a running timer. */
    public function stop(int $id): TimerDTO
    {
        return TimerDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/stop", []));
    }
}