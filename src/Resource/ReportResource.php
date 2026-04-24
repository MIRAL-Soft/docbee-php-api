<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ReportDTO;

/**
 * Provides access to Docbee Report records.
 *
 * @extends AbstractResource<ReportDTO>
 */
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

final class ReportResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'report';
    protected string $dtoClass = ReportDTO::class;
    protected string $listKey  = 'report';

    public function getElementTypes(): array    { return $this->http->get("{$this->endpoint}/elementType"); }
    public function getElementType(int $id): array    { return $this->http->get("{$this->endpoint}/elementType/{$id}"); }
    public function getParameterTypes(): array  { return $this->http->get("{$this->endpoint}/parameterType"); }
    public function getParameterType(int $id): array  { return $this->http->get("{$this->endpoint}/parameterType/{$id}"); }
}
