<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ErrorLog record.
 */
final class ErrorLogDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $content,
        private readonly ?string $level,
        private readonly ?string $code,
        private readonly ?string $date,
        private readonly bool $processed,
        private readonly ?int $occurrenceCount,
        private readonly array $dataMap,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            content: self::toString($data['content'] ?? null),
            level: self::toString($data['level'] ?? null),
            code: self::toString($data['code'] ?? null),
            date: self::toString($data['date'] ?? null),
            processed: self::toBool($data['processed'] ?? false),
            occurrenceCount: self::toInt($data['occurrenceCount'] ?? null),
            dataMap: $data['dataMap'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'content' => $this->content,
            'level' => $this->level,
            'code' => $this->code,
            'date' => $this->date,
            'processed' => $this->processed,
            'occurrenceCount' => $this->occurrenceCount,
            'dataMap' => $this->dataMap,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getContent(): ?string { return $this->content; }
    public function getLevel(): ?string { return $this->level; }
    public function getCode(): ?string { return $this->code; }
    public function getDate(): ?string { return $this->date; }
    public function isProcessed(): bool { return $this->processed; }
    public function getOccurrenceCount(): ?int { return $this->occurrenceCount; }
    public function getDataMap(): array { return $this->dataMap; }
}