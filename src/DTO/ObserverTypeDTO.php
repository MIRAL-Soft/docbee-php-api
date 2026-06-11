<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ObserverType record.
 */
final class ObserverTypeDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific observerType */
        private readonly ?int $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** deactivated */
        private ?bool $deactivated,
        /** name */
        private ?string $name,
        /** sendDocBeeDocumentMessage */
        private ?bool $sendDocBeeDocumentMessage,
        /** sendProtocolMessage */
        private ?bool $sendProtocolMessage
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            deactivated: isset($data['deactivated']) ? self::toBool($data['deactivated']) : null,
            name: self::toString($data['name'] ?? null),
            sendDocBeeDocumentMessage: isset($data['sendDocBeeDocumentMessage']) ? self::toBool($data['sendDocBeeDocumentMessage']) : null,
            sendProtocolMessage: isset($data['sendProtocolMessage']) ? self::toBool($data['sendProtocolMessage']) : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'deactivated' => $this->deactivated,
            'name' => $this->name,
            'sendDocBeeDocumentMessage' => $this->sendDocBeeDocumentMessage,
            'sendProtocolMessage' => $this->sendProtocolMessage
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function isDeactivated(): ?bool { return $this->deactivated; }
    public function getName(): ?string { return $this->name; }
    public function getSendDocBeeDocumentMessage(): ?bool { return $this->sendDocBeeDocumentMessage; }
    public function getSendProtocolMessage(): ?bool { return $this->sendProtocolMessage; }
}