<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CostEstimationTemplate record.
 */
final class CostEstimationTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $mode,
        private readonly array $tasks,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            mode: self::toString($data['mode'] ?? null),
            tasks: $data['tasks'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'mode' => $this->mode,
            'tasks' => $this->tasks,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getMode(): ?string { return $this->mode; }
    public function getTasks(): array { return $this->tasks; }
}