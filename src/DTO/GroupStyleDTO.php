<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee GroupStyle record.
 */
final class GroupStyleDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific groupStyle */
        private ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** showHeader */
        private ?bool $showHeader,
        /** showBorder */
        private ?bool $showBorder,
        /** weight */
        private ?float $weight,
        /** valueColumnCount */
        private ?int $valueColumnCount,
        /** headerSize */
        private ?int $headerSize,
        /** headerColor */
        private ?string $headerColor,
        /** headerFontStyle */
        private ?string $headerFontStyle,
        /** headerAlignment */
        private ?string $headerAlignment,
        /** borderThickness */
        private ?float $borderThickness,
        /** borderColor */
        private ?string $borderColor,
        /** startOnNewPage */
        private ?bool $startOnNewPage,
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
            showHeader: isset($data['showHeader']) ? self::toBool($data['showHeader']) : null,
            showBorder: isset($data['showBorder']) ? self::toBool($data['showBorder']) : null,
            weight: self::toFloat($data['weight'] ?? null),
            valueColumnCount: self::toInt($data['valueColumnCount'] ?? null),
            headerSize: self::toInt($data['headerSize'] ?? null),
            headerColor: self::toString($data['headerColor'] ?? null),
            headerFontStyle: self::toString($data['headerFontStyle'] ?? null),
            headerAlignment: self::toString($data['headerAlignment'] ?? null),
            borderThickness: self::toFloat($data['borderThickness'] ?? null),
            borderColor: self::toString($data['borderColor'] ?? null),
            startOnNewPage: isset($data['startOnNewPage']) ? self::toBool($data['startOnNewPage']) : null,
            isDefault: isset($data['isDefault']) ? self::toBool($data['isDefault']) : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'showHeader' => $this->showHeader,
            'showBorder' => $this->showBorder,
            'weight' => $this->weight,
            'valueColumnCount' => $this->valueColumnCount,
            'headerSize' => $this->headerSize,
            'headerColor' => $this->headerColor,
            'headerFontStyle' => $this->headerFontStyle,
            'headerAlignment' => $this->headerAlignment,
            'borderThickness' => $this->borderThickness,
            'borderColor' => $this->borderColor,
            'startOnNewPage' => $this->startOnNewPage,
            'isDefault' => $this->isDefault
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getShowHeader(): ?bool { return $this->showHeader; }
    public function getShowBorder(): ?bool { return $this->showBorder; }
    public function getWeight(): ?float { return $this->weight; }
    public function getValueColumnCount(): ?int { return $this->valueColumnCount; }
    public function getHeaderSize(): ?int { return $this->headerSize; }
    public function getHeaderColor(): ?string { return $this->headerColor; }
    public function getHeaderFontStyle(): ?string { return $this->headerFontStyle; }
    public function getHeaderAlignment(): ?string { return $this->headerAlignment; }
    public function getBorderThickness(): ?float { return $this->borderThickness; }
    public function getBorderColor(): ?string { return $this->borderColor; }
    public function getStartOnNewPage(): ?bool { return $this->startOnNewPage; }
    public function getIsDefault(): ?bool { return $this->isDefault; }
}
