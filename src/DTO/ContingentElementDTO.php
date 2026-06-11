<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ContingentElement record.
 */
final class ContingentElementDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific element */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** contingentItem identifier */
        private ?int $contingentItem,
        /** date */
        private ?string $date,
        /** postType */
        private ?string $postType,
        /** postedMoney */
        private ?float $postedMoney,
        /** postedTime */
        private ?int $postedTime,
        /** task identifier */
        private ?int $task
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            contingentItem: self::toInt($data['contingentItem'] ?? null),
            date: self::toString($data['date'] ?? null),
            postType: self::toString($data['postType'] ?? null),
            postedMoney: self::toFloat($data['postedMoney'] ?? null),
            postedTime: self::toInt($data['postedTime'] ?? null),
            task: self::toInt($data['task'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'contingentItem' => $this->contingentItem,
            'date' => $this->date,
            'postType' => $this->postType,
            'postedMoney' => $this->postedMoney,
            'postedTime' => $this->postedTime,
            'task' => $this->task
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getContingentItem(): ?int { return $this->contingentItem; }
    public function getDate(): ?string { return $this->date; }
    public function getPostType(): ?string { return $this->postType; }
    public function getPostedMoney(): ?float { return $this->postedMoney; }
    public function getPostedTime(): ?int { return $this->postedTime; }
    public function getTask(): ?int { return $this->task; }
}