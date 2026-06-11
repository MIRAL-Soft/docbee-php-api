<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ContingentItem record.
 */
final class ContingentItemDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific item */
        private readonly ?int    $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** money */
        private ?float           $money,
        /** time in milliseconds */
        private ?int             $time,
        /** comment */
        private ?string          $comment,
        /** invoiceNumber */
        private ?string          $invoiceNumber,
        /** posted date */
        private ?string          $postedDate,
        /** expired date */
        private ?string          $expiredDate,
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:            self::toInt($data['id'] ?? null),
            created:       self::toString($data['created'] ?? null),
            modified:      self::toString($data['modified'] ?? null),
            link:          self::toString($data['link'] ?? null),
            money:         isset($data['money']) ? (float) $data['money'] : null,
            time:          self::toInt($data['time'] ?? null),
            comment:       self::toString($data['comment'] ?? null),
            invoiceNumber: self::toString($data['invoiceNumber'] ?? null),
            postedDate:    self::toString($data['postedDate'] ?? null),
            expiredDate:   self::toString($data['expiredDate'] ?? null),
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'money'         => $this->money,
            'time'          => $this->time,
            'comment'       => $this->comment,
            'invoiceNumber' => $this->invoiceNumber,
            'postedDate'    => $this->postedDate,
            'expiredDate'   => $this->expiredDate,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int             { return $this->id; }
    public function getCreated(): ?string     { return $this->created; }
    public function getModified(): ?string    { return $this->modified; }
    public function getLink(): ?string        { return $this->link; }
    public function getMoney(): ?float        { return $this->money; }
    public function getTime(): ?int           { return $this->time; }
    public function getComment(): ?string     { return $this->comment; }
    public function getInvoiceNumber(): ?string { return $this->invoiceNumber; }
    public function getPostedDate(): ?string  { return $this->postedDate; }
    public function getExpiredDate(): ?string { return $this->expiredDate; }
}
