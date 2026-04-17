<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee SiteConfig record.
 */
final class SiteConfigDTO extends AbstractDTO
{
    public function __construct(
        /** REST API Link */
        private readonly ?string $link,
        /** configuration data */
        private ?array $config,
        /** forced flag (UserSiteConfig extension, readOnly) */
        private readonly ?bool $forced = null,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            link:   self::toString($data['link'] ?? null),
            config: isset($data['config']) && is_array($data['config']) ? $data['config'] : null,
            forced: isset($data['forced']) ? self::toBool($data['forced']) : null,
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'config' => $this->config,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return null; }
    public function getLink(): ?string { return $this->link; }
    public function getConfig(): ?array { return $this->config; }
    public function getForcedConfig(): ?bool { return $this->forced; }
}
