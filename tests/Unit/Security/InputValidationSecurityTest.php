<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Security;

use InvalidArgumentException;
use miralsoft\docbee\api\Config\DocbeeConfig;
use miralsoft\docbee\api\Query\FilterOperator;
use miralsoft\docbee\api\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Security tests for input validation across {@see DocbeeConfig} and {@see QueryBuilder}.
 *
 * Focuses on injection prevention (URL injection via tenant, query field injection),
 * token redaction, whitespace handling, and URL construction correctness.
 */
final class InputValidationSecurityTest extends TestCase
{
    // -------------------------------------------------------------------------
    // DocbeeConfig – tenant URL injection prevention
    // -------------------------------------------------------------------------

    public function testTenantWithDotIsRejected(): void
    {
        // Dots would allow subdomain injection: "evil.com" → https://evil.com.docbee.com
        // A domain resolution quirk could be exploited to redirect traffic.
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'evil.com', token: 'tok');
    }

    public function testTenantWithSchemeIsRejected(): void
    {
        // Prevents: "https://attacker.com/path?tenant=" → URL hijacking
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'https://attacker.com/foo', token: 'tok');
    }

    public function testTenantWithSlashIsRejected(): void
    {
        // Path traversal / URL segment injection
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'company/../../evil', token: 'tok');
    }

    public function testTenantWithAtSignIsRejected(): void
    {
        // @ allows basic-auth injection: https://user:pass@host/
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'user@evil', token: 'tok');
    }

    public function testTenantWithHashIsRejected(): void
    {
        // # would truncate the URL path
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'company#fragment', token: 'tok');
    }

    public function testTenantWithQuestionMarkIsRejected(): void
    {
        // ? would inject query parameters into the base URL
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'company?evil=1', token: 'tok');
    }

    public function testTenantWithSpaceIsRejectedAfterTrim(): void
    {
        // A tenant with only spaces is rejected even after trimming
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: '   ', token: 'tok');
    }

    public function testTenantWithEncodedDotIsRejected(): void
    {
        // URL-encoded dot — should be rejected because '%' is not alphanumeric
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'evil%2Ecom', token: 'tok');
    }

    public function testTenantStartingWithDigitIsAccepted(): void
    {
        // Subdomains may start with a digit (RFC 1123 relaxation)
        $config = new DocbeeConfig(tenant: '42co', token: 'tok');
        $this->assertSame('42co', $config->getTenant());
    }

    public function testTenantWithHyphenAndUnderscoreIsAccepted(): void
    {
        $config = new DocbeeConfig(tenant: 'my-company_99', token: 'tok');
        $this->assertSame('my-company_99', $config->getTenant());
    }

    public function testLeadingAndTrailingWhitespaceIsTrimmedFromTenant(): void
    {
        $config = new DocbeeConfig(tenant: '  company  ', token: 'tok');
        $this->assertSame('company', $config->getTenant());
    }

    public function testLeadingAndTrailingWhitespaceIsTrimmedFromToken(): void
    {
        $config = new DocbeeConfig(tenant: 'company', token: '  mytoken  ');
        $this->assertSame('mytoken', $config->getToken());
    }

    public function testBaseUrlContainsTenantExactlyOnce(): void
    {
        $config = new DocbeeConfig(tenant: 'acme', token: 'tok');
        $url    = $config->getBaseUrl();

        $this->assertSame(1, substr_count($url, 'acme'), 'Tenant must appear exactly once in base URL.');
        $this->assertStringStartsWith('https://', $url);
        $this->assertStringContainsString('acme.docbee.com', $url);
    }

    public function testBaseUrlDoesNotContainDoubleSlashAfterHost(): void
    {
        $config = new DocbeeConfig(tenant: 'acme', token: 'tok');
        $path   = str_replace('https://', '', $config->getBaseUrl());

        $this->assertStringNotContainsString('//', $path);
    }

    // -------------------------------------------------------------------------
    // DocbeeConfig – token redaction
    // -------------------------------------------------------------------------

    public function testTokenIsNotExposedViaDebugInfo(): void
    {
        $config    = new DocbeeConfig(tenant: 'acme', token: 'super-secret-12345');
        $debugInfo = $config->__debugInfo();

        $this->assertArrayHasKey('token', $debugInfo);
        $this->assertSame('***REDACTED***', $debugInfo['token']);
        $this->assertStringNotContainsString('super-secret-12345', implode('', $debugInfo));
    }

    public function testTokenIsNotExposedInBaseUrl(): void
    {
        $config = new DocbeeConfig(tenant: 'acme', token: 'super-secret-12345');
        $this->assertStringNotContainsString('super-secret-12345', $config->getBaseUrl());
    }

    public function testEmptyTokenIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'acme', token: '');
    }

    public function testWhitespaceOnlyTokenIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'acme', token: '   ');
    }

    // -------------------------------------------------------------------------
    // DocbeeConfig – timeout validation
    // -------------------------------------------------------------------------

    public function testZeroTimeoutIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'acme', token: 'tok', timeout: 0);
    }

    public function testNegativeTimeoutIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'acme', token: 'tok', timeout: -1);
    }

    public function testZeroConnectTimeoutIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'acme', token: 'tok', connectTimeout: 0);
    }

    // -------------------------------------------------------------------------
    // QueryBuilder – field name injection prevention
    // -------------------------------------------------------------------------

    public function testFilterFieldWithDotIsRejected(): void
    {
        // Prevents: "table.column" or "../../path" field injection
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->filter('field.name', FilterOperator::EQ, 'x');
    }

    public function testFilterFieldWithSqlInjectionIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->filter("field'; DROP TABLE--", FilterOperator::EQ, 'x');
    }

    public function testFilterFieldWithBracketIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->filter('field[0]', FilterOperator::EQ, 'x');
    }

    public function testFilterFieldStartingWithDigitIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->filter('1field', FilterOperator::EQ, 'x');
    }

    public function testSortFieldWithDotIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->sort('related.field');
    }

    public function testSortFieldWithInjectionAttemptIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->sort("id; DELETE FROM tickets");
    }

    // -------------------------------------------------------------------------
    // QueryBuilder – filter values are URL-encoded (no query-string injection)
    // -------------------------------------------------------------------------

    public function testFilterValueWithAmpersandIsUrlEncoded(): void
    {
        // An unencoded '&' in a filter value could inject additional query params.
        $qs = QueryBuilder::new()->filterEq('name', 'Acme&evil=1')->build();
        $this->assertStringNotContainsString('evil=1', $qs);
        // The value must be percent-encoded
        $this->assertStringContainsString('Acme', $qs);
    }

    public function testFilterValueWithEqualsSignIsUrlEncoded(): void
    {
        // An unencoded '=' could break query parameter parsing.
        $qs = QueryBuilder::new()->filterEq('token', 'a=b=c')->build();
        $this->assertStringContainsString('a%3Db%3Dc', $qs);
    }

    public function testFilterValueWithNullByteIsHandledSafely(): void
    {
        // Null bytes can truncate strings in some contexts – must not crash.
        $qs = QueryBuilder::new()->filterEq('name', "value\0null")->build();
        $this->assertIsString($qs);
    }

    // -------------------------------------------------------------------------
    // QueryBuilder – boolean normalization
    // -------------------------------------------------------------------------

    public function testFilterBoolTrueNormalisedToOne(): void
    {
        $qs = QueryBuilder::new()->filterEq('active', true)->build();
        $this->assertStringContainsString('active-eq=1', $qs);
    }

    public function testFilterBoolFalseNormalisedToZero(): void
    {
        $qs = QueryBuilder::new()->filterEq('active', false)->build();
        $this->assertStringContainsString('active-eq=0', $qs);
    }
}
