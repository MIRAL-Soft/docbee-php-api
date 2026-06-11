<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DocBeeScript record.
 */
final class DocBeeScriptDTO extends AbstractDTO
{
    public function __construct(
        /** REST API Link */
        private readonly ?string $link,
        /** file identifier */
        private readonly ?int $logFile,
        /** description */
        private ?string $description,
        /** name */
        private ?string $name,
        /** @var DocBeeScriptParameterDTO[]|null params data */
        private ?array $params,
        /** script */
        private ?string $script
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            link: self::toString($data['link'] ?? null),
            logFile: self::toInt($data['logFile'] ?? null),
            description: self::toString($data['description'] ?? null),
            name: self::toString($data['name'] ?? null),
            params: self::toDtoList($data['params'] ?? null, DocBeeScriptParameterDTO::class),
            script: self::toString($data['script'] ?? null)
        );
    }

    #[\Override]
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