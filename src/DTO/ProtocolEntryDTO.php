<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolEntry record.
 */
final class ProtocolEntryDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocolEntry */
        private readonly ?int $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** entryMapping identifier */
        private readonly ?int $entryMapping,
        /** groupIdx */
        private readonly ?int $groupIdx,
        /** mapped protocol document id */
        private readonly ?int $protocolDocumentIdValue,
        /** booleanValue */
        private ?bool $booleanValue,
        /** date */
        private ?string $date,
        /** doubleValue */
        private ?float $doubleValue,
        /** element identifier */
        private ?int $element,
        /** file identifiers */
        private ?array $files,
        /** longValue */
        private ?int $longValue,
        /** observer identifiers */
        private ?array $observers,
        /** selectionValue identifiers */
        private ?array $selectionValues,
        /** text */
        private ?string $text
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            entryMapping: self::toInt($data['entryMapping'] ?? null),
            groupIdx: self::toInt($data['groupIdx'] ?? null),
            protocolDocumentIdValue: self::toInt($data['protocolDocumentIdValue'] ?? null),
            booleanValue: isset($data['booleanValue']) ? self::toBool($data['booleanValue']) : null,
            date: self::toString($data['date'] ?? null),
            doubleValue: self::toFloat($data['doubleValue'] ?? null),
            element: self::toInt($data['element'] ?? null),
            files: isset($data['files']) && is_array($data['files']) ? $data['files'] : null,
            longValue: self::toInt($data['longValue'] ?? null),
            observers: isset($data['observers']) && is_array($data['observers']) ? $data['observers'] : null,
            selectionValues: isset($data['selectionValues']) && is_array($data['selectionValues']) ? $data['selectionValues'] : null,
            text: self::toString($data['text'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'booleanValue' => $this->booleanValue,
            'date' => $this->date,
            'doubleValue' => $this->doubleValue,
            'element' => $this->element,
            'files' => $this->files,
            'longValue' => $this->longValue,
            'observers' => $this->observers,
            'selectionValues' => $this->selectionValues,
            'text' => $this->text
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getEntryMapping(): ?int { return $this->entryMapping; }
    public function getGroupIdx(): ?int { return $this->groupIdx; }
    public function getProtocolDocumentIdValue(): ?int { return $this->protocolDocumentIdValue; }
    public function getBooleanValue(): ?bool { return $this->booleanValue; }
    public function getDate(): ?string { return $this->date; }
    public function getDoubleValue(): ?float { return $this->doubleValue; }
    public function getElement(): ?int { return $this->element; }
    public function getFiles(): ?array { return $this->files; }
    public function getLongValue(): ?int { return $this->longValue; }
    public function getObservers(): ?array { return $this->observers; }
    public function getSelectionValues(): ?array { return $this->selectionValues; }
    public function getText(): ?string { return $this->text; }
}