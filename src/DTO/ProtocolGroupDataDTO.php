<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolGroupData record.
 */
final class ProtocolGroupDataDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocolGroupData */
        private readonly ?int $id,
        /** finished */
        private readonly ?bool $finished,
        /** templateGroup */
        private readonly ?int $templateGroup
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            finished: isset($data['finished']) ? self::toBool($data['finished']) : null,
            templateGroup: self::toInt($data['templateGroup'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getFinished(): ?bool { return $this->finished; }
    public function getTemplateGroup(): ?int { return $this->templateGroup; }
}