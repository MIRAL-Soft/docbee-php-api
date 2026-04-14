<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee File record.
 */
final class FileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific file */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** file extension */
        private readonly ?string $fileExtension,
        /** MIME type */
        private readonly ?string $mimeType,
        /** file name */
        private readonly ?string $name,
        /** public file flag */
        private ?bool $publicFile
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            fileExtension: self::toString($data['fileExtension'] ?? null),
            mimeType: self::toString($data['mimeType'] ?? null),
            name: self::toString($data['name'] ?? null),
            publicFile: isset($data['publicFile']) ? self::toBool($data['publicFile']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'publicFile' => $this->publicFile,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getFileExtension(): ?string { return $this->fileExtension; }
    public function getMimeType(): ?string { return $this->mimeType; }
    public function getName(): ?string { return $this->name; }
    public function isPublicFile(): ?bool { return $this->publicFile; }
}
