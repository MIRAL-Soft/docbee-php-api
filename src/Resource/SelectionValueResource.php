<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\SelectionValueDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee SelectionValue records (sub-resource).
 *
 * @extends AbstractResource<SelectionValueDTO>
 */
final class SelectionValueResource extends AbstractResource
{
    protected string $dtoClass = SelectionValueDTO::class;
    protected string $listKey  = 'selectionValue';

    public function __construct(HttpClientInterface $http, int $selectionCategoryId)
    {
        $this->endpoint = "v1/selectionCategory/{$selectionCategoryId}/selectionValue";
        parent::__construct($http);
    }
}