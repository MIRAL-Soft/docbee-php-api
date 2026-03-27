<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Base class for all Docbee API data transfer objects.
 *
 * Every DTO implements {@see fromArray()} which maps a raw API response array
 * to a typed PHP object, and {@see toArray()} which serialises the DTO back
 * into the array format accepted by the API's write endpoints.
 *
 * DTOs implement {@see \JsonSerializable} so they can be passed directly to
 * `json_encode()`:
 *
 * ```php
 * $ticket = $client->tickets()->find(42);
 * echo json_encode($ticket); // serialises via toArray()
 * ```
 */
abstract class AbstractDTO implements \JsonSerializable
{
    /**
     * Creates a DTO instance from a raw API response array.
     *
     * @param array<string, mixed> $data
     * @return static
     */
    abstract public static function fromArray(array $data): static;

    /**
     * Serialises the DTO to an array suitable for API write requests.
     * Read-only API fields (id, link, etc.) are excluded automatically.
     *
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;

    /**
     * Returns the numeric Docbee ID of this record, or null for new records.
     */
    abstract public function getId(): ?int;

    /**
     * Implements {@see \JsonSerializable} so DTOs work with `json_encode()` out of the box.
     *
     * Delegates to {@see toArray()}, which excludes read-only server fields.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Safely casts a value to int, returning null when the value is null/empty.
     *
     * @param mixed $value
     */
    protected static function toInt(mixed $value): ?int
    {
        return ($value === null || $value === '') ? null : (int) $value;
    }

    /**
     * Safely casts a value to bool.
     *
     * @param mixed $value
     */
    protected static function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        return in_array($value, [1, '1', 'true', 'yes'], true);
    }

    /**
     * Safely casts a value to string, returning null when the value is null.
     *
     * @param mixed $value
     */
    protected static function toString(mixed $value): ?string
    {
        return $value === null ? null : (string) $value;
    }
}
