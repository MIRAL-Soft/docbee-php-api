<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PdfLayout record.
 */
final class PdfLayoutDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private mixed $firstPageLayout,
        private ?bool $isDefault,
        private ?string $name,
        private mixed $otherPageLayout,
        private ?array $settings,
        private ?int $tableConfigStorage,
        private ?string $type
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            firstPageLayout: $data['firstPageLayout'] ?? null,
            isDefault: isset($data['isDefault']) ? self::toBool($data['isDefault']) : null,
            name: self::toString($data['name'] ?? null),
            otherPageLayout: $data['otherPageLayout'] ?? null,
            settings: isset($data['settings']) && is_array($data['settings']) ? $data['settings'] : null,
            tableConfigStorage: self::toInt($data['tableConfigStorage'] ?? null),
            type: self::toString($data['type'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'firstPageLayout' => $this->firstPageLayout,
            'isDefault' => $this->isDefault,
            'name' => $this->name,
            'otherPageLayout' => $this->otherPageLayout,
            'settings' => $this->settings,
            'tableConfigStorage' => $this->tableConfigStorage,
            'type' => $this->type
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getFirstPageLayout(): mixed { return $this->firstPageLayout; }
    public function getIsDefault(): ?bool { return $this->isDefault; }
    public function getName(): ?string { return $this->name; }
    public function getOtherPageLayout(): mixed { return $this->otherPageLayout; }
    public function getSettings(): ?array { return $this->settings; }
    public function getTableConfigStorage(): ?int { return $this->tableConfigStorage; }
    public function getType(): ?string { return $this->type; }
}