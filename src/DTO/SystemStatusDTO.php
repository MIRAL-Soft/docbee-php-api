<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee SystemStatus response.
 */
final class SystemStatusDTO extends AbstractDTO
{
    public function __construct(
        /** @var list<mixed>|null list of jobs */
        private ?array $jobs
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            jobs: isset($data['jobs']) && is_array($data['jobs']) ? $data['jobs'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter(['jobs' => $this->jobs], fn($v) => $v !== null);
    }

    public function getId(): ?int { return null; }
    /** @return list<mixed>|null */
    public function getJobs(): ?array { return $this->jobs; }
}
