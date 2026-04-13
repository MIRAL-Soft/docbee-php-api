<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PermissionGroup record.
 */
final class PermissionGroupDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?string $type,
        private ?bool $enabled,
        private ?array $favoritTableConfigStorages,
        private ?bool $force2FA,
        private ?string $name,
        private ?array $roles,
        private ?array $tableConfigStorages
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            type: self::toString($data['type'] ?? null),
            enabled: isset($data['enabled']) ? self::toBool($data['enabled']) : null,
            favoritTableConfigStorages: isset($data['favoritTableConfigStorages']) && is_array($data['favoritTableConfigStorages']) ? $data['favoritTableConfigStorages'] : null,
            force2FA: isset($data['force2FA']) ? self::toBool($data['force2FA']) : null,
            name: self::toString($data['name'] ?? null),
            roles: isset($data['roles']) && is_array($data['roles']) ? $data['roles'] : null,
            tableConfigStorages: isset($data['tableConfigStorages']) && is_array($data['tableConfigStorages']) ? $data['tableConfigStorages'] : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'enabled' => $this->enabled,
            'favoritTableConfigStorages' => $this->favoritTableConfigStorages,
            'force2FA' => $this->force2FA,
            'name' => $this->name,
            'roles' => $this->roles,
            'tableConfigStorages' => $this->tableConfigStorages
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getType(): ?string { return $this->type; }
    public function getEnabled(): ?bool { return $this->enabled; }
    public function getFavoritTableConfigStorages(): ?array { return $this->favoritTableConfigStorages; }
    public function getForce2FA(): ?bool { return $this->force2FA; }
    public function getName(): ?string { return $this->name; }
    public function getRoles(): ?array { return $this->roles; }
    public function getTableConfigStorages(): ?array { return $this->tableConfigStorages; }
}