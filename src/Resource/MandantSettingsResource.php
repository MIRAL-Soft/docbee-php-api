<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\MandantSettingsDTO;
use miralsoft\docbee\api\DTO\WorkingDaysDTO;

/** Provides access to Docbee mandant (tenant) settings. */
final class MandantSettingsResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    public function get(): MandantSettingsDTO
    {
        return MandantSettingsDTO::fromArray($this->http->get('mandantSettings'));
    }

    /** @param array<string, mixed> $data */
    public function update(array $data): MandantSettingsDTO
    {
        return MandantSettingsDTO::fromArray($this->http->put('mandantSettings', $data));
    }

    public function getWorkingDays(): WorkingDaysDTO
    {
        return WorkingDaysDTO::fromArray($this->http->get('mandantSettings/workingDays'));
    }

    /** @param array<string, mixed> $data */
    public function updateWorkingDays(array $data): WorkingDaysDTO
    {
        return WorkingDaysDTO::fromArray($this->http->put('mandantSettings/workingDays', $data));
    }
}
