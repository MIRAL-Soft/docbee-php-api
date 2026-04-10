<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Security;

use GuzzleHttp\Psr7\Response;
use miralsoft\docbee\api\Exception\AuthenticationException;
use miralsoft\docbee\api\Exception\DocbeeApiException;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Exception\RateLimitException;
use miralsoft\docbee\api\Exception\ServerException;
use miralsoft\docbee\api\Exception\ValidationException;
use miralsoft\docbee\api\Util\ResponseParser;
use PHPUnit\Framework\TestCase;

/**
 * Security tests for {@see ResponseParser}.
 *
 * Verifies correct exception mapping, safe handling of malformed or
 * adversarial response bodies, and that scalar-only extraction prevents
 * type confusion when reading error messages.
 */
final class ResponseParserSecurityTest extends TestCase
{
    // -------------------------------------------------------------------------
    // parse() – body decoding
    // -------------------------------------------------------------------------

    public function testParseReturnsEmptyArrayForEmptyBody(): void
    {
        $response = new Response(200, [], '');
        $this->assertSame([], ResponseParser::parse($response));
    }

    public function testParseReturnsEmptyArrayForNullBody(): void
    {
        $response = new Response(200, [], 'null');
        $this->assertSame([], ResponseParser::parse($response));
    }

    public function testParseMalformedJsonThrowsDocbeeApiException(): void
    {
        $this->expectException(DocbeeApiException::class);
        $response = new Response(200, [], '{not valid json}');
        ResponseParser::parse($response);
    }

    public function testParseJsonStringThrowsDocbeeApiException(): void
    {
        // A bare JSON string is not an object – must not be returned as an array.
        $this->expectException(DocbeeApiException::class);
        $response = new Response(200, [], '"just a string"');
        ResponseParser::parse($response);
    }

    public function testParseJsonNumberThrowsDocbeeApiException(): void
    {
        $this->expectException(DocbeeApiException::class);
        $response = new Response(200, [], '42');
        ResponseParser::parse($response);
    }

    public function testParseJsonBooleanThrowsDocbeeApiException(): void
    {
        $this->expectException(DocbeeApiException::class);
        $response = new Response(200, [], 'true');
        ResponseParser::parse($response);
    }

    public function testParseValidObjectReturnsArray(): void
    {
        $response = new Response(200, [], json_encode(['id' => 1, 'name' => 'Test']));
        $result   = ResponseParser::parse($response);
        $this->assertSame(['id' => 1, 'name' => 'Test'], $result);
    }

    // -------------------------------------------------------------------------
    // throw() – HTTP status → exception type mapping
    // -------------------------------------------------------------------------

    public function test401ThrowsAuthenticationException(): void
    {
        $this->expectException(AuthenticationException::class);
        ResponseParser::throw(401, '', '/ticket');
    }

    public function test403ThrowsAuthenticationException(): void
    {
        $this->expectException(AuthenticationException::class);
        ResponseParser::throw(403, '', '/ticket');
    }

    public function test404ThrowsNotFoundException(): void
    {
        $this->expectException(NotFoundException::class);
        ResponseParser::throw(404, '', '/ticket/99');
    }

    public function test429ThrowsRateLimitException(): void
    {
        $this->expectException(RateLimitException::class);
        ResponseParser::throw(429, '', '/ticket');
    }

    public function test422ThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);
        ResponseParser::throw(422, '', '/ticket');
    }

    public function test400ThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);
        ResponseParser::throw(400, '', '/ticket');
    }

    public function test500ThrowsServerException(): void
    {
        $this->expectException(ServerException::class);
        ResponseParser::throw(500, '', '/ticket');
    }

    public function test503ThrowsServerException(): void
    {
        $this->expectException(ServerException::class);
        ResponseParser::throw(503, '', '/ticket');
    }

    // -------------------------------------------------------------------------
    // throw() – error body message extraction
    // -------------------------------------------------------------------------

    public function testErrorBodyMessageFieldIsExtracted(): void
    {
        $body = json_encode(['message' => 'Token is invalid.']);
        try {
            ResponseParser::throw(401, $body, '/ticket');
        } catch (AuthenticationException $e) {
            $this->assertSame('Token is invalid.', $e->getMessage());
            return;
        }
        $this->fail('Expected AuthenticationException.');
    }

    public function testErrorBodyErrorFieldIsExtractedAsFallback(): void
    {
        $body = json_encode(['error' => 'Unauthorized']);
        try {
            ResponseParser::throw(401, $body, '/ticket');
        } catch (AuthenticationException $e) {
            $this->assertSame('Unauthorized', $e->getMessage());
            return;
        }
        $this->fail('Expected AuthenticationException.');
    }

    public function testErrorBodyArrayMessageIsNotExtractedAsString(): void
    {
        // If 'message' is an array, we must NOT produce the string "Array".
        $body = json_encode(['message' => ['detail1', 'detail2']]);
        try {
            ResponseParser::throw(422, $body, '/ticket');
        } catch (ValidationException $e) {
            $this->assertStringNotContainsString('Array', $e->getMessage());
            return;
        }
        $this->fail('Expected ValidationException.');
    }

    public function testErrorBodyObjectMessageIsNotExtractedAsString(): void
    {
        $body = json_encode(['message' => ['code' => 42, 'text' => 'Bad input']]);
        try {
            ResponseParser::throw(422, $body, '/ticket');
        } catch (ValidationException $e) {
            $this->assertStringNotContainsString('Array', $e->getMessage());
            return;
        }
        $this->fail('Expected ValidationException.');
    }

    public function testNonJsonErrorBodyProducesFallbackMessage(): void
    {
        try {
            ResponseParser::throw(500, 'Internal Server Error (plain text)', '/ticket');
        } catch (ServerException $e) {
            // Should fall back to generic message, not expose the raw body.
            $this->assertStringContainsString('500', $e->getMessage());
            return;
        }
        $this->fail('Expected ServerException.');
    }

    public function testEmptyErrorBodyProducesFallbackMessage(): void
    {
        try {
            ResponseParser::throw(404, '', '/ticket/99');
        } catch (NotFoundException $e) {
            $this->assertStringContainsString('404', $e->getMessage());
            return;
        }
        $this->fail('Expected NotFoundException.');
    }

    // -------------------------------------------------------------------------
    // Exception carries correct metadata
    // -------------------------------------------------------------------------

    public function testExceptionCarriesStatusCode(): void
    {
        try {
            ResponseParser::throw(403, '', '/secret');
        } catch (AuthenticationException $e) {
            $this->assertSame(403, $e->getStatusCode());
            return;
        }
        $this->fail('Expected AuthenticationException.');
    }

    public function testExceptionCarriesRequestUrl(): void
    {
        try {
            ResponseParser::throw(404, '', '/ticket/42');
        } catch (NotFoundException $e) {
            $this->assertSame('/ticket/42', $e->getRequestUrl());
            return;
        }
        $this->fail('Expected NotFoundException.');
    }

    public function testExceptionCarriesResponseBody(): void
    {
        $body = json_encode(['message' => 'Not found']);
        try {
            ResponseParser::throw(404, $body, '/ticket/42');
        } catch (NotFoundException $e) {
            $this->assertSame($body, $e->getResponseBody());
            return;
        }
        $this->fail('Expected NotFoundException.');
    }
}
