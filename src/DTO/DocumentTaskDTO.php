<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a task (checklist item) within a Docbee document.
 */
final class DocumentTaskDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?int    $docBeeDocument,
        private readonly ?string $title,
        private readonly ?string $description,
        private readonly ?bool   $completed,
        private readonly ?string $completedAt,
        private readonly ?int    $completedBy,
        private readonly ?string $createdAt,
        private readonly ?string $changedAt,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:              self::toInt($data['id'] ?? null),
            docBeeDocument:  self::toInt($data['docBeeDocument'] ?? null),
            title:           self::toString($data['title'] ?? null),
            description:     self::toString($data['description'] ?? null),
            completed:       isset($data['completed']) ? self::toBool($data['completed']) : null,
            completedAt:     self::toString($data['completedAt'] ?? null),
            completedBy:     self::toInt($data['completedBy'] ?? null),
            createdAt:       self::toString($data['createdAt'] ?? null),
            changedAt:       self::toString($data['changedAt'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'docBeeDocument' => $this->docBeeDocument,
            'title'          => $this->title,
            'description'    => $this->description,
            'completed'      => $this->completed,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int              { return $this->id; }
    public function getDocBeeDocument(): ?int  { return $this->docBeeDocument; }
    public function getTitle(): ?string        { return $this->title; }
    public function getDescription(): ?string  { return $this->description; }
    public function isCompleted(): ?bool       { return $this->completed; }
    public function getCompletedAt(): ?string  { return $this->completedAt; }
    public function getCompletedBy(): ?int     { return $this->completedBy; }
    public function getCreatedAt(): ?string    { return $this->createdAt; }
    public function getChangedAt(): ?string    { return $this->changedAt; }
}
