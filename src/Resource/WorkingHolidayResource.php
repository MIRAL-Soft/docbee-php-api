<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\WorkingHolidayDTO;

/**
 * Provides access to Docbee WorkingHoliday records (sub-resource of mandantSettings/workingDays).
 *
 * @extends AbstractResource<WorkingHolidayDTO>
 */
final class WorkingHolidayResource extends AbstractResource
{
    protected string $dtoClass = WorkingHolidayDTO::class;
    protected string $listKey  = 'workingHoliday';

    public function __construct(HttpClientInterface $http)
    {
        $this->endpoint = 'mandantSettings/workingDays/workingHoliday';
        parent::__construct($http);
    }
}
