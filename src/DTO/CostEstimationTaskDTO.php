<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CostEstimationTask record.
 */
final class CostEstimationTaskDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $ticket,
        private readonly ?int $docBeeDocument,
        private readonly array $subTasks,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            ticket: self::toInt($data['ticket'] ?? null),
            docBeeDocument: self::toInt($data['docBeeDocument'] ?? null),
            subTasks: $data['subTasks'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'ticket' => $this->ticket,
            'docBeeDocument' => $this->docBeeDocument,
            'subTasks' => $this->subTasks,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getTicket(): ?int { return $this->ticket; }
    public function getDocBeeDocument(): ?int { return $this->docBeeDocument; }
    public function getSubTasks(): array { return $this->subTasks; }
}