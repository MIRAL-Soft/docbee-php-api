<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee RangePlanningTemplateItemElement record.
 */
final class RangePlanningTemplateItemElementDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific rangePlanningTemplateItemElement */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** docBeeDocumentTemplate */
        private ?int $docBeeDocumentTemplate
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            docBeeDocumentTemplate: self::toInt($data['docBeeDocumentTemplate'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'docBeeDocumentTemplate' => $this->docBeeDocumentTemplate
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getDocBeeDocumentTemplate(): ?int { return $this->docBeeDocumentTemplate; }
}
