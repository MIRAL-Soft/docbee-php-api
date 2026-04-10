<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolEntry record.
 */
final class ProtocolEntryDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $entryMapping,
        private readonly ?int $groupIdx,
        private readonly ?int $protocolDocumentIdValue,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            entryMapping: self::toInt($data['entryMapping'] ?? null),
            groupIdx: self::toInt($data['groupIdx'] ?? null),
            protocolDocumentIdValue: self::toInt($data['protocolDocumentIdValue'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'entryMapping' => $this->entryMapping,
            'groupIdx' => $this->groupIdx,
            'protocolDocumentIdValue' => $this->protocolDocumentIdValue,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getEntryMapping(): ?int { return $this->entryMapping; }
    public function getGroupIdx(): ?int { return $this->groupIdx; }
    public function getProtocolDocumentIdValue(): ?int { return $this->protocolDocumentIdValue; }
}