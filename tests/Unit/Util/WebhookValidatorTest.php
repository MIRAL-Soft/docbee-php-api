<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Util;

use InvalidArgumentException;
use miralsoft\docbee\api\Resource\WebhookResource;
use miralsoft\docbee\api\Util\WebhookValidator;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see WebhookValidator}.
 */
final class WebhookValidatorTest extends TestCase
{
    public function testValidPayloadPassesValidation(): void
    {
        $payload = ['type' => 'CREATE_TICKET', 'customer' => 42];
        WebhookValidator::validate($payload); // no exception
        $this->assertTrue(true);
    }

    public function testEmptyPayloadThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validate([]);
    }

    public function testNullPayloadThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validate(null);
    }

    public function testMissingTypeFieldThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validate(['customer' => 42]);
    }

    public function testValidTypePassesValidation(): void
    {
        foreach (WebhookResource::TYPES as $type) {
            WebhookValidator::validateType($type); // no exception
        }
        $this->assertTrue(true);
    }

    public function testInvalidTypeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validateType('UNKNOWN_EVENT');
    }

    public function testNullTypeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validateType(null);
    }

    public function testIsValidTypeReturnsTrueForValidTypes(): void
    {
        $this->assertTrue(WebhookValidator::isValidType('CREATE_TICKET'));
        $this->assertTrue(WebhookValidator::isValidType('CREATE_DOCUMENT'));
    }

    public function testIsValidTypeReturnsFalseForInvalidTypes(): void
    {
        $this->assertFalse(WebhookValidator::isValidType('BOGUS'));
        $this->assertFalse(WebhookValidator::isValidType(null));
        $this->assertFalse(WebhookValidator::isValidType(''));
    }

    public function testParseAndValidateDecodesValidJson(): void
    {
        $body    = json_encode(['type' => 'CREATE_TICKET', 'customer' => 1]);
        $payload = WebhookValidator::parseAndValidate($body);
        $this->assertSame('CREATE_TICKET', $payload['type']);
    }

    public function testParseAndValidateThrowsOnInvalidJson(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('not-json');
    }

    public function testParseAndValidateThrowsOnEmptyBody(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('');
    }

    public function testExplicitNullTypeFieldThrows(): void
    {
        // A payload with 'type' => null must be rejected, even though array_key_exists()
        // would return true. The previous isset() check would have the same result here,
        // but the explicit null check makes the contract clear.
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validate(['type' => null, 'customer' => 42]);
    }
}
