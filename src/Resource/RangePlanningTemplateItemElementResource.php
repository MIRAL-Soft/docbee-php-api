<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\RangePlanningTemplateItemElementDTO;

/**
 * Provides access to Docbee RangePlanningTemplateItemElement records (sub-resource of rangePlanningTemplate item).
 *
 * @extends AbstractResource<RangePlanningTemplateItemElementDTO>
 */
final class RangePlanningTemplateItemElementResource extends AbstractResource
{
    protected string $dtoClass = RangePlanningTemplateItemElementDTO::class;
    protected string $listKey  = 'rangePlanningTemplateItemElement';

    public function __construct(HttpClientInterface $http, int $templateId, int $itemId)
    {
        $this->endpoint = "v1/rangePlanningTemplate/{$templateId}/item/{$itemId}/element";
        parent::__construct($http);
    }
}
