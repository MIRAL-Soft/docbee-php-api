<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ObserverTypeDTO;

/**
 * Provides access to Docbee ObserverType records (sub-resource of observerCategory).
 *
 * @extends AbstractResource<ObserverTypeDTO>
 */
final class ObserverCategoryObserverTypeResource extends AbstractResource
{
    protected string $dtoClass = ObserverTypeDTO::class;
    protected string $listKey  = 'observerType';

    public function __construct(HttpClientInterface $http, int $observerCategoryId)
    {
        $this->endpoint = "observerCategory/{$observerCategoryId}/observerType";
        parent::__construct($http);
    }
}
