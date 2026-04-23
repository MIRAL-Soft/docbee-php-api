<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\RangePlanningTemplateItemDTO;

/**
 * Provides access to Docbee RangePlanningTemplateItem records (sub-resource of rangePlanningTemplate).
 *
 * @extends AbstractResource<RangePlanningTemplateItemDTO>
 */
final class RangePlanningTemplateItemResource extends AbstractResource
{
    protected string $dtoClass = RangePlanningTemplateItemDTO::class;
    protected string $listKey  = 'rangePlanningTemplateItem';

    public function __construct(HttpClientInterface $http, int $templateId)
    {
        $this->endpoint = "rangePlanningTemplate/{$templateId}/item";
        parent::__construct($http);
    }
}
