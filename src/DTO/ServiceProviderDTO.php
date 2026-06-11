<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ServiceProvider record.
 */
final class ServiceProviderDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific serviceProvider */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** deactivated */
        private readonly ?bool $deactivated,
        /** email */
        private readonly ?string $email,
        /** number */
        private readonly ?string $number,
        /** shortName */
        private readonly ?string $shortName,
        /** @var CustomFieldValueDTO[]|null list of customFieldValues */
        private ?array $customFields,
        /** name */
        private ?string $name
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            deactivated: isset($data['deactivated']) ? self::toBool($data['deactivated']) : null,
            email: self::toString($data['email'] ?? null),
            number: self::toString($data['number'] ?? null),
            shortName: self::toString($data['shortName'] ?? null),
            customFields: self::toDtoList($data['customFields'] ?? null, CustomFieldValueDTO::class),
            name: self::toString($data['name'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'customFields' => $this->customFields,
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function isDeactivated(): ?bool { return $this->deactivated; }
    public function getEmail(): ?string { return $this->email; }
    public function getNumber(): ?string { return $this->number; }
    public function getShortName(): ?string { return $this->shortName; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getName(): ?string { return $this->name; }
}