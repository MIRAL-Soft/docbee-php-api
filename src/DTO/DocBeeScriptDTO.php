<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DocBeeScript record.
 */
final class DocBeeScriptDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $logFile,
        private readonly array $params,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            logFile: self::toInt($data['logFile'] ?? null),
            params: $data['params'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'logFile' => $this->logFile,
            'params' => $this->params,
        ], fn($v) => $v !== null);
    }

    public function getLogFile(): ?int { return $this->logFile; }
    public function getParams(): array { return $this->params; }
    public function getId(): ?int { return null; }
}