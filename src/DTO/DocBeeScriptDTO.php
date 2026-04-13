<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DocBeeScript record.
 */
final class DocBeeScriptDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?string $link,
        private readonly ?int $logFile,
        private ?string $description,
        private ?string $name,
        private ?array $params,
        private ?string $script
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            link: self::toString($data['link'] ?? null),
            logFile: self::toInt($data['logFile'] ?? null),
            description: self::toString($data['description'] ?? null),
            name: self::toString($data['name'] ?? null),
            params: isset($data['params']) && is_array($data['params']) ? $data['params'] : null,
            script: self::toString($data['script'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'description' => $this->description,
            'name' => $this->name,
            'params' => $this->params,
            'script' => $this->script
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int      { return null; }
    public function getLink(): ?string { return $this->link; }
    public function getLogFile(): ?int { return $this->logFile; }
    public function getDescription(): ?string { return $this->description; }
    public function getName(): ?string { return $this->name; }
    public function getParams(): ?array { return $this->params; }
    public function getScript(): ?string { return $this->script; }
}