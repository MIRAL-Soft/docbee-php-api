<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ContingentElement record.
 */
final class ContingentElementDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $date,
        private readonly ?int $task,
        private readonly ?int $contingentItem,
        private readonly ?string $postType,
        private readonly ?float $postedMoney,
        private readonly ?int $postedTime,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            date: self::toString($data['date'] ?? null),
            task: self::toInt($data['task'] ?? null),
            contingentItem: self::toInt($data['contingentItem'] ?? null),
            postType: self::toString($data['postType'] ?? null),
            postedMoney: self::toFloat($data['postedMoney'] ?? null),
            postedTime: self::toInt($data['postedTime'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'date' => $this->date,
            'task' => $this->task,
            'contingentItem' => $this->contingentItem,
            'postType' => $this->postType,
            'postedMoney' => $this->postedMoney,
            'postedTime' => $this->postedTime,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getDate(): ?string { return $this->date; }
    public function getTask(): ?int { return $this->task; }
    public function getContingentItem(): ?int { return $this->contingentItem; }
    public function getPostType(): ?string { return $this->postType; }
    public function getPostedMoney(): ?float { return $this->postedMoney; }
    public function getPostedTime(): ?int { return $this->postedTime; }
}