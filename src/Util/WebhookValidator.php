<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Util;

use InvalidArgumentException;
use miralsoft\docbee\api\Resource\WebhookResource;

/**
 * Validates incoming Docbee webhook payload data before processing.
 *
 * ⚠ **Structure only — NOT authenticity.** This validator checks that the payload
 * is well-formed JSON with the expected fields. It does NOT verify that the request
 * actually came from Docbee: the Docbee API provides no signature/HMAC mechanism
 * for webhooks, so anyone who knows your receiver URL can submit a valid-looking
 * payload. Mitigate on your side: keep the receiver URL secret and unguessable
 * (e.g. include a random token in the path), restrict by source IP where possible,
 * and treat payload content as untrusted input (re-fetch the referenced record via
 * the API instead of trusting embedded data).
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
 * // Payload is structurally safe to process (authenticity NOT verified — see above)
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

        // json_validate() only confirms the body is valid JSON — it does not
        // guarantee a JSON object. Guard against scalars (e.g. "42", "null")
        // which would cause a TypeError in validate(?array).
        if (!is_array($payload)) {
            throw new InvalidArgumentException('Webhook body must be a JSON object, not a scalar or null.');
        }

        self::validate($payload);
        return $payload;
    }
}
