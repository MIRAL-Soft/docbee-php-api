<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DocBeeDocumentTemplateProfile record.
 */
final class DocBeeDocumentTemplateProfileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific docBeeDocumentTemplateProfile */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /**
         * list of docBeeDocumentTemplates
         * @var array<int|string, mixed>|null
         */
        private ?array $docBeeDocumentTemplates
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            docBeeDocumentTemplates: isset($data['docBeeDocumentTemplates']) && is_array($data['docBeeDocumentTemplates']) ? $data['docBeeDocumentTemplates'] : null
        );
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'docBeeDocumentTemplates' => $this->docBeeDocumentTemplates
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getDocBeeDocumentTemplates(): ?array { return $this->docBeeDocumentTemplates; }
}
