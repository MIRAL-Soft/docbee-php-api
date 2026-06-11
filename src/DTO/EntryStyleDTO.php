<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee EntryStyle record.
 */
final class EntryStyleDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific entryStyle */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** underline */
        private ?bool $underline,
        /** weight */
        private ?float $weight,
        /** descriptionSize */
        private ?int $descriptionSize,
        /** descriptionColor */
        private ?string $descriptionColor,
        /** descriptionFontStyle */
        private ?string $descriptionFontStyle,
        /** descriptionAlignment */
        private ?string $descriptionAlignment,
        /** contentSize */
        private ?int $contentSize,
        /** contentColor */
        private ?string $contentColor,
        /** contentFontStyle */
        private ?string $contentFontStyle,
        /** contentAlignment */
        private ?string $contentAlignment,
        /** isDefault */
        private ?bool $isDefault
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            underline: isset($data['underline']) ? self::toBool($data['underline']) : null,
            weight: self::toFloat($data['weight'] ?? null),
            descriptionSize: self::toInt($data['descriptionSize'] ?? null),
            descriptionColor: self::toString($data['descriptionColor'] ?? null),
            descriptionFontStyle: self::toString($data['descriptionFontStyle'] ?? null),
            descriptionAlignment: self::toString($data['descriptionAlignment'] ?? null),
            contentSize: self::toInt($data['contentSize'] ?? null),
            contentColor: self::toString($data['contentColor'] ?? null),
            contentFontStyle: self::toString($data['contentFontStyle'] ?? null),
            contentAlignment: self::toString($data['contentAlignment'] ?? null),
            isDefault: isset($data['isDefault']) ? self::toBool($data['isDefault']) : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'underline' => $this->underline,
            'weight' => $this->weight,
            'descriptionSize' => $this->descriptionSize,
            'descriptionColor' => $this->descriptionColor,
            'descriptionFontStyle' => $this->descriptionFontStyle,
            'descriptionAlignment' => $this->descriptionAlignment,
            'contentSize' => $this->contentSize,
            'contentColor' => $this->contentColor,
            'contentFontStyle' => $this->contentFontStyle,
            'contentAlignment' => $this->contentAlignment,
            'isDefault' => $this->isDefault
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getUnderline(): ?bool { return $this->underline; }
    public function getWeight(): ?float { return $this->weight; }
    public function getDescriptionSize(): ?int { return $this->descriptionSize; }
    public function getDescriptionColor(): ?string { return $this->descriptionColor; }
    public function getDescriptionFontStyle(): ?string { return $this->descriptionFontStyle; }
    public function getDescriptionAlignment(): ?string { return $this->descriptionAlignment; }
    public function getContentSize(): ?int { return $this->contentSize; }
    public function getContentColor(): ?string { return $this->contentColor; }
    public function getContentFontStyle(): ?string { return $this->contentFontStyle; }
    public function getContentAlignment(): ?string { return $this->contentAlignment; }
    public function getIsDefault(): ?bool { return $this->isDefault; }
}
