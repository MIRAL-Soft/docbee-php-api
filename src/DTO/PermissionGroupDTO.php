<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PermissionGroup record.
 */
final class PermissionGroupDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific permissionGroup */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** type */
        private readonly ?string $type,
        /** enabled */
        private ?bool $enabled,
        /**
         * Shared tableConfigStorage identifiers, which every user with this permissionGroup gets subscribed to and shown as favorit
         * @var array<int|string, mixed>|null
         */
        private ?array $favoritTableConfigStorages,
        /** If 'true' all users 'use2FA' are forced set to 'true' and can not be changed */
        private ?bool $force2FA,
        /** name */
        private ?string $name,
        /**
         * list of roles
         * @var array<int|string, mixed>|null
         */
        private ?array $roles,
        /**
         * Shared tableConfigStorage identifiers, which every user with this permissionGroup gets subscribed to
         * @var array<int|string, mixed>|null
         */
        private ?array $tableConfigStorages
    ) {}

    #[\Override]
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

    #[\Override]
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
    /**
     * @return array<int|string, mixed>|null
     */
    public function getFavoritTableConfigStorages(): ?array { return $this->favoritTableConfigStorages; }
    public function getForce2FA(): ?bool { return $this->force2FA; }
    public function getName(): ?string { return $this->name; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getRoles(): ?array { return $this->roles; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getTableConfigStorages(): ?array { return $this->tableConfigStorages; }
}