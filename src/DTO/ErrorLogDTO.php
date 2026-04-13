<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ErrorLog record.
 */
final class ErrorLogDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific errorLog */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** internal identifier of the type of error */
        private ?string $code,
        /** Content of the error message */
        private ?string $content,
        /** map of data */
        private ?array $dataMap,
        /** occurrence date */
        private ?string $date,
        /** level of the error */
        private ?string $level,
        /** count of occurrences */
        private ?int $occurrenceCount,
        /** processed status */
        private ?bool $processed
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            code: self::toString($data['code'] ?? null),
            content: self::toString($data['content'] ?? null),
            dataMap: isset($data['dataMap']) && is_array($data['dataMap']) ? $data['dataMap'] : null,
            date: self::toString($data['date'] ?? null),
            level: self::toString($data['level'] ?? null),
            occurrenceCount: self::toInt($data['occurrenceCount'] ?? null),
            processed: isset($data['processed']) ? self::toBool($data['processed']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'code' => $this->code,
            'content' => $this->content,
            'dataMap' => $this->dataMap,
            'date' => $this->date,
            'level' => $this->level,
            'occurrenceCount' => $this->occurrenceCount,
            'processed' => $this->processed
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getCode(): ?string { return $this->code; }
    public function getContent(): ?string { return $this->content; }
    public function getDataMap(): ?array { return $this->dataMap; }
    public function getDate(): ?string { return $this->date; }
    public function getLevel(): ?string { return $this->level; }
    public function getOccurrenceCount(): ?int { return $this->occurrenceCount; }
    public function getProcessed(): ?bool { return $this->processed; }
}