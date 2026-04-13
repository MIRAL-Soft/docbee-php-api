<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketStatus record.
 */
final class TicketStatusDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private ?string $behaviour,
        private ?string $color,
        private ?bool $escalate,
        private ?string $name,
        private ?bool $sendCustomerMail,
        private ?bool $sendOwnerMail,
        private ?bool $syncToApp
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            behaviour: self::toString($data['behaviour'] ?? null),
            color: self::toString($data['color'] ?? null),
            escalate: isset($data['escalate']) ? self::toBool($data['escalate']) : null,
            name: self::toString($data['name'] ?? null),
            sendCustomerMail: isset($data['sendCustomerMail']) ? self::toBool($data['sendCustomerMail']) : null,
            sendOwnerMail: isset($data['sendOwnerMail']) ? self::toBool($data['sendOwnerMail']) : null,
            syncToApp: isset($data['syncToApp']) ? self::toBool($data['syncToApp']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'behaviour' => $this->behaviour,
            'color' => $this->color,
            'escalate' => $this->escalate,
            'name' => $this->name,
            'sendCustomerMail' => $this->sendCustomerMail,
            'sendOwnerMail' => $this->sendOwnerMail,
            'syncToApp' => $this->syncToApp
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getBehaviour(): ?string { return $this->behaviour; }
    public function getColor(): ?string { return $this->color; }
    public function getEscalate(): ?bool { return $this->escalate; }
    public function getName(): ?string { return $this->name; }
    public function getSendCustomerMail(): ?bool { return $this->sendCustomerMail; }
    public function getSendOwnerMail(): ?bool { return $this->sendOwnerMail; }
    public function isSyncToApp(): ?bool { return $this->syncToApp; }
}