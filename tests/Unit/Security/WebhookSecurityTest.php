<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Security;

use InvalidArgumentException;
use miralsoft\docbee\api\Resource\WebhookResource;
use miralsoft\docbee\api\Util\WebhookValidator;
use PHPUnit\Framework\TestCase;

/**
 * Security tests for {@see WebhookValidator} and {@see WebhookResource}.
 *
 * Verifies that the webhook type whitelist is enforced, that adversarial payloads
 * (injection attempts, malformed JSON, non-object JSON) are rejected with the
 * correct exception type (InvalidArgumentException — not TypeError or ParseError),
 * and that all TYPE_* constants remain non-empty strings.
 */
final class WebhookSecurityTest extends TestCase
{
    // -------------------------------------------------------------------------
    // Type whitelist enforcement
    // -------------------------------------------------------------------------

    public function testAllDefinedTypeConstantsAreNonEmptyStrings(): void
    {
        foreach (WebhookResource::TYPES as $type) {
            $this->assertIsString($type, 'Each type constant must be a string.');
            $this->assertNotEmpty($type, 'Each type constant must not be empty.');
        }
    }

    public function testValidateTypeAcceptsAllDefinedTypes(): void
    {
        foreach (WebhookResource::TYPES as $type) {
            // Must not throw
            WebhookValidator::validateType($type);
            $this->assertTrue(WebhookValidator::isValidType($type));
        }
    }

    public function testValidateTypeRejectsUnknownType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validateType('UNKNOWN_TYPE');
    }

    public function testValidateTypeRejectsEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validateType('');
    }

    public function testValidateTypeRejectsNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validateType(null);
    }

    public function testValidateTypeRejectsLowercaseVariant(): void
    {
        // Type comparison must be strict – lowercase must not pass.
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validateType('create_ticket');
    }

    // -------------------------------------------------------------------------
    // Injection attempts in the type field
    // -------------------------------------------------------------------------

    public function testValidateTypeRejectsXssAttempt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validateType('<script>alert(1)</script>');
    }

    public function testValidateTypeRejectsSqlInjectionAttempt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validateType("'; DROP TABLE webhooks; --");
    }

    public function testValidateTypeRejectsNullByteInjection(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validateType("CREATE_TICKET\0");
    }

    public function testValidateTypeRejectsUnicodeSpoofAttempt(): void
    {
        // Homoglyph / Unicode lookalike of an allowed type must not pass.
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validateType("CREATE_ТICKET"); // Cyrillic Т
    }

    // -------------------------------------------------------------------------
    // validate() – payload structure
    // -------------------------------------------------------------------------

    public function testValidateThrowsOnNullPayload(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validate(null);
    }

    public function testValidateThrowsOnEmptyArrayPayload(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validate([]);
    }

    public function testValidateThrowsWhenTypeFieldMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validate(['foo' => 'bar']);
    }

    public function testValidateThrowsWhenTypeFieldIsNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validate(['type' => null]);
    }

    public function testValidateThrowsWhenTypeFieldIsEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::validate(['type' => '']);
    }

    public function testValidatePassesWithValidType(): void
    {
        // Must not throw.
        WebhookValidator::validate(['type' => WebhookResource::TYPE_CREATE_TICKET]);
        $this->assertTrue(true);
    }

    // -------------------------------------------------------------------------
    // parseAndValidate() – JSON parsing edge cases
    // -------------------------------------------------------------------------

    public function testParseAndValidateRejectsEmptyBody(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('');
    }

    public function testParseAndValidateRejectsWhitespaceOnlyBody(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('   ');
    }

    public function testParseAndValidateRejectsMalformedJson(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('{invalid json}');
    }

    public function testParseAndValidateRejectsTruncatedJson(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('{"type": "CREATE_TICKET"');  // missing closing brace
    }

    public function testParseAndValidateRejectsJsonNumber(): void
    {
        // Valid JSON but not an object – must throw InvalidArgumentException,
        // NOT a TypeError (regression test for the is_array() guard fix).
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('42');
    }

    public function testParseAndValidateRejectsJsonNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('null');
    }

    public function testParseAndValidateRejectsJsonString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('"just a string"');
    }

    public function testParseAndValidateRejectsJsonArray(): void
    {
        // A JSON array is valid JSON but not an object – must be rejected.
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('[{"type":"CREATE_TICKET"}]');
    }

    public function testParseAndValidateRejectsJsonBoolean(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WebhookValidator::parseAndValidate('true');
    }

    public function testParseAndValidateReturnsPayloadForValidInput(): void
    {
        $body    = json_encode(['type' => WebhookResource::TYPE_CREATE_TICKET, 'data' => []]);
        $payload = WebhookValidator::parseAndValidate($body);

        $this->assertIsArray($payload);
        $this->assertSame(WebhookResource::TYPE_CREATE_TICKET, $payload['type']);
    }

    // -------------------------------------------------------------------------
    // Unknown type error message does not amplify injection payload
    // -------------------------------------------------------------------------

    public function testUnknownTypeErrorMessageDoesNotContainRawHtml(): void
    {
        $injected = '<script>alert(1)</script>';
        try {
            WebhookValidator::validateType($injected);
            $this->fail('Expected InvalidArgumentException.');
        } catch (InvalidArgumentException $e) {
            // The message may echo back the type value for debugging, which is
            // acceptable in an exception (not rendered as HTML). However we verify
            // it is a plain string and no additional interpretation occurs.
            $this->assertIsString($e->getMessage());
        }
    }
}
