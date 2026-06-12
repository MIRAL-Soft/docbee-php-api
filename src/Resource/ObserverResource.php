<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ObserverDTO;

/**
 * Provides access to Docbee Observer records.
 *
 * @extends AbstractResource<ObserverDTO>
 */
final class ObserverResource extends AbstractResource
{
    protected string $endpoint = 'observer';
    protected string $dtoClass = ObserverDTO::class;
    protected string $listKey  = 'observer';

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}
