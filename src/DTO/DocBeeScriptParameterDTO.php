<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DocBeeScriptParameter record.
 */
final class DocBeeScriptParameterDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific DocBeeScriptParameter */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** a boolean value if type is BOOLEAN */
        private ?bool $booleanValue,
        /** description */
        private ?string $description,
        /** a double value if type is DOUBLE */
        private ?float $doubleValue,
        /** a file identifier if type if FILE */
        private ?int $fileValue,
        /** key */
        private ?string $key,
        /** a long value if type is LONG */
        private ?int $longValue,
        /** a text if type is STRING or LIST_STRING */
        private ?string $textValue,
        /** type */
        private ?string $type
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            booleanValue: isset($data['booleanValue']) ? self::toBool($data['booleanValue']) : null,
            description: self::toString($data['description'] ?? null),
            doubleValue: self::toFloat($data['doubleValue'] ?? null),
            fileValue: self::toInt($data['fileValue'] ?? null),
            key: self::toString($data['key'] ?? null),
            longValue: self::toInt($data['longValue'] ?? null),
            textValue: self::toString($data['textValue'] ?? null),
            type: self::toString($data['type'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'booleanValue' => $this->booleanValue,
            'description' => $this->description,
            'doubleValue' => $this->doubleValue,
            'fileValue' => $this->fileValue,
            'key' => $this->key,
            'longValue' => $this->longValue,
            'textValue' => $this->textValue,
            'type' => $this->type
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getBooleanValue(): ?bool { return $this->booleanValue; }
    public function getDescription(): ?string { return $this->description; }
    public function getDoubleValue(): ?float { return $this->doubleValue; }
    public function getFileValue(): ?int { return $this->fileValue; }
    public function getKey(): ?string { return $this->key; }
    public function getLongValue(): ?int { return $this->longValue; }
    public function getTextValue(): ?string { return $this->textValue; }
    public function getType(): ?string { return $this->type; }
}