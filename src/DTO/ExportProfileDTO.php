<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ExportProfile record.
 */
final class ExportProfileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific exportProfile */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** exportType */
        private ?string $exportType,
        /** name */
        private ?string $fileName,
        /** name */
        private ?string $name
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            exportType: self::toString($data['exportType'] ?? null),
            fileName: self::toString($data['fileName'] ?? null),
            name: self::toString($data['name'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'exportType' => $this->exportType,
            'fileName' => $this->fileName,
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getExportType(): ?string { return $this->exportType; }
    public function getFileName(): ?string { return $this->fileName; }
    public function getName(): ?string { return $this->name; }
}