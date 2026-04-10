<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CostEstimationSubTask record.
 */
final class CostEstimationSubTaskDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $docBeeDocumentTask,
        private readonly ?float $estimateBuffer,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            docBeeDocumentTask: self::toInt($data['docBeeDocumentTask'] ?? null),
            estimateBuffer: self::toFloat($data['estimateBuffer'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'docBeeDocumentTask' => $this->docBeeDocumentTask,
            'estimateBuffer' => $this->estimateBuffer,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getDocBeeDocumentTask(): ?int { return $this->docBeeDocumentTask; }
    public function getEstimateBuffer(): ?float { return $this->estimateBuffer; }
}