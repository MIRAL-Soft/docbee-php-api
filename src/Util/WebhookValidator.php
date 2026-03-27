<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Util;

use InvalidArgumentException;
use miralsoft\docbee\api\Resource\WebhookResource;

/**
 * Validates incoming Docbee webhook payload data before processing.
 *
 * Use this in your receiving application to safely handle webhook submissions:
 *
 * ```php
 * // In your webhook receiver endpoint:
 * $payload = json_decode(file_get_contents('php://input'), true);
 *
 * try {
 *     WebhookValidator::validate($payload);
 *     WebhookValidator::validateType($payload['type'] ?? null);
 * } catch (InvalidArgumentException $e) {
 *     http_response_code(400);
 *     exit($e->getMessage());
 * }
 *
 * // Payload is safe to process
 * processWebhook($payload);
 * ```
 */
final class WebhookValidator
{
    /**
     * Validates the structure of an incoming webhook payload.
     *
     * Checks that all required top-level fields are present and non-empty.
     *
     * @param array<string, mixed>|null $payload The decoded JSON payload.
     * @throws InvalidArgumentException when the payload is invalid.
     */
    public static function validate(?array $payload): void
    {
        if ($payload === null || empty($payload)) {
            throw new InvalidArgumentException('Webhook payload must not be empty.');
        }

        $required = ['type'];
        foreach ($required as $field) {
            // Use array_key_exists() to distinguish between a missing key and an
            // explicit null value — isset() treats both identically.
            if (!array_key_exists($field, $payload) || $payload[$field] === null || $payload[$field] === '') {
                throw new InvalidArgumentException(
                    "Webhook payload is missing required field '{$field}'."
                );
            }
        }
    }

    /**
     * Validates that the webhook event type is one of the allowed values.
     *
     * @param string|null $type The type value from the payload.
     * @throws InvalidArgumentException when the type is invalid.
     */
    public static function validateType(?string $type): void
    {
        if ($type === null || $type === '') {
            throw new InvalidArgumentException('Webhook type must not be empty.');
        }

        if (!in_array($type, WebhookResource::TYPES, true)) {
            throw new InvalidArgumentException(
                "Unknown webhook type '{$type}'. Allowed: " . implode(', ', WebhookResource::TYPES)
            );
        }
    }

    /**
     * Returns true when the type is valid, false otherwise (no exception).
     *
     * @param string|null $type
     */
    public static function isValidType(?string $type): bool
    {
        return $type !== null && in_array($type, WebhookResource::TYPES, true);
    }

    /**
     * Parses and validates a raw JSON webhook body string.
     *
     * Convenience method combining JSON decoding + structure validation.
     *
     * @return array<string, mixed>
     * @throws InvalidArgumentException on invalid JSON or invalid structure.
     */
    public static function parseAndValidate(string $body): array
    {
        if (trim($body) === '') {
            throw new InvalidArgumentException('Webhook body must not be empty.');
        }

        if (!json_validate($body)) {
            throw new InvalidArgumentException('Webhook body is not valid JSON.');
        }

        $payload = json_decode($body, true);

        self::validate($payload);
        return $payload;
    }
}
