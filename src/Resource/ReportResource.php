<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ReportDTO;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee Report records.
 *
 * @extends AbstractResource<ReportDTO>
 */
final class ReportResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'report';
    protected string $dtoClass = ReportDTO::class;
    protected string $listKey  = 'report';

    /** @return array<string, mixed> */
    public function getElementTypes(): array    { return $this->http->get("{$this->endpoint}/elementType"); }
    /** @return array<string, mixed> */
    public function getElementType(int $id): array    { return $this->http->get("{$this->endpoint}/elementType/{$id}"); }
    /** @return array<string, mixed> */
    public function getParameterTypes(): array  { return $this->http->get("{$this->endpoint}/parameterType"); }
    /** @return array<string, mixed> */
    public function getParameterType(int $id): array  { return $this->http->get("{$this->endpoint}/parameterType/{$id}"); }
}
