<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CostEstimationTaskTemplate record.
 */
final class CostEstimationTaskTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly array $subTasks,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            subTasks: $data['subTasks'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'subTasks' => $this->subTasks,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getSubTasks(): array { return $this->subTasks; }
}