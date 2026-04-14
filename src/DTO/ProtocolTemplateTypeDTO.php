<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolTemplateType record.
 */
final class ProtocolTemplateTypeDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocolTemplateType */
        private readonly ?int $id,
        /** modified timestamp */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** createLabel */
        private ?string $createLabel,
        /** specialDocumentNumberPattern */
        private ?string $specialDocumentNumberPattern,
        /** withSpecialDocumentNumber */
        private ?bool $withSpecialDocumentNumber,
        /** showInNavigation */
        private ?bool $showInNavigation,
        /** showInNavigationForCustomer */
        private ?bool $showInNavigationForCustomer,
        /** displayAsTileInApp */
        private ?bool $displayAsTileInApp,
        /** icon */
        private ?int $icon,
        /** navigationIcon */
        private ?int $navigationIcon
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            createLabel: self::toString($data['createLabel'] ?? null),
            specialDocumentNumberPattern: self::toString($data['specialDocumentNumberPattern'] ?? null),
            withSpecialDocumentNumber: self::toBool($data['withSpecialDocumentNumber'] ?? null),
            showInNavigation: self::toBool($data['showInNavigation'] ?? null),
            showInNavigationForCustomer: self::toBool($data['showInNavigationForCustomer'] ?? null),
            displayAsTileInApp: self::toBool($data['displayAsTileInApp'] ?? null),
            icon: self::toInt($data['icon'] ?? null),
            navigationIcon: self::toInt($data['navigationIcon'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'createLabel' => $this->createLabel,
            'specialDocumentNumberPattern' => $this->specialDocumentNumberPattern,
            'withSpecialDocumentNumber' => $this->withSpecialDocumentNumber,
            'showInNavigation' => $this->showInNavigation,
            'showInNavigationForCustomer' => $this->showInNavigationForCustomer,
            'displayAsTileInApp' => $this->displayAsTileInApp,
            'icon' => $this->icon,
            'navigationIcon' => $this->navigationIcon
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getCreateLabel(): ?string { return $this->createLabel; }
    public function getSpecialDocumentNumberPattern(): ?string { return $this->specialDocumentNumberPattern; }
    public function getWithSpecialDocumentNumber(): ?bool { return $this->withSpecialDocumentNumber; }
    public function getShowInNavigation(): ?bool { return $this->showInNavigation; }
    public function getShowInNavigationForCustomer(): ?bool { return $this->showInNavigationForCustomer; }
    public function getDisplayAsTileInApp(): ?bool { return $this->displayAsTileInApp; }
    public function getIcon(): ?int { return $this->icon; }
    public function getNavigationIcon(): ?int { return $this->navigationIcon; }
}
