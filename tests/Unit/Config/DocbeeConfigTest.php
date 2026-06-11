<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Config;

use InvalidArgumentException;
use miralsoft\docbee\api\Config\DocbeeConfig;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see DocbeeConfig}.
 */
final class DocbeeConfigTest extends TestCase
{
    public function testConstructorSetsProperties(): void
    {
        $config = new DocbeeConfig(tenant: 'mycompany', token: 'secret');

        $this->assertSame('mycompany', $config->getTenant());
        $this->assertSame('secret', $config->getToken());
        $this->assertSame('https://mycompany.docbee.com/restApi/v1/', $config->getBaseUrl());
        $this->assertSame(30, $config->getTimeout());
        $this->assertSame(10, $config->getConnectTimeout());
        $this->assertSame(3, $config->getMaxRetries());
    }

    public function testConstructorWithCustomValues(): void
    {
        $config = new DocbeeConfig(
            tenant:         'acme',
            token:          'tok',
            timeout:        60,
            connectTimeout: 5,
            maxRetries:     5,
        );

        $this->assertSame(60, $config->getTimeout());
        $this->assertSame(5, $config->getConnectTimeout());
        $this->assertSame(5, $config->getMaxRetries());
    }

    public function testThrowsOnEmptyTenant(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: '', token: 'tok');
    }

    public function testThrowsOnEmptyToken(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'mycompany', token: '');
    }

    public function testThrowsOnInvalidTimeout(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'mycompany', token: 'tok', timeout: 0);
    }

    public function testFromArrayCreatesConfig(): void
    {
        $config = DocbeeConfig::fromArray([
            'tenant' => 'mycompany',
            'token'  => 'secret',
        ]);

        $this->assertSame('mycompany', $config->getTenant());
        $this->assertSame('secret', $config->getToken());
    }

    public function testFromArrayThrowsOnMissingTenant(): void
    {
        $this->expectException(InvalidArgumentException::class);
        DocbeeConfig::fromArray(['token' => 'tok']);
    }

    public function testFromArrayThrowsOnMissingToken(): void
    {
        $this->expectException(InvalidArgumentException::class);
        DocbeeConfig::fromArray(['tenant' => 'mycompany']);
    }

    public function testFromEnvCreatesConfig(): void
    {
        putenv('DOCBEE_TENANT=mycompany');
        putenv('DOCBEE_TOKEN=secret');

        $config = DocbeeConfig::fromEnv();

        $this->assertSame('mycompany', $config->getTenant());
        $this->assertSame('secret', $config->getToken());

        putenv('DOCBEE_TENANT');
        putenv('DOCBEE_TOKEN');
    }

    public function testFromEnvThrowsWhenVarsNotSet(): void
    {
        // fromEnv() now also falls back to $_ENV (set by tests/bootstrap.php when
        // tests/.env.test exists), so BOTH sources must be cleared — and restored
        // afterwards so later integration tests still find their credentials.
        $backup = [
            'DOCBEE_TENANT' => $_ENV['DOCBEE_TENANT'] ?? null,
            'DOCBEE_TOKEN'  => $_ENV['DOCBEE_TOKEN'] ?? null,
        ];
        putenv('DOCBEE_TENANT');
        putenv('DOCBEE_TOKEN');
        unset($_ENV['DOCBEE_TENANT'], $_ENV['DOCBEE_TOKEN']);

        try {
            $this->expectException(InvalidArgumentException::class);
            DocbeeConfig::fromEnv();
        } finally {
            foreach ($backup as $key => $value) {
                if ($value !== null) {
                    $_ENV[$key] = $value;
                    putenv("{$key}={$value}");
                }
            }
        }
    }

    public function testFromEnvFallsBackToEnvSuperglobal(): void
    {
        // getenv() empty but $_ENV populated (variables_order without "E",
        // dotenv loaders) — fromEnv() must still find the credentials.
        $backup = [
            'DOCBEE_TENANT' => $_ENV['DOCBEE_TENANT'] ?? null,
            'DOCBEE_TOKEN'  => $_ENV['DOCBEE_TOKEN'] ?? null,
        ];
        putenv('DOCBEE_TENANT');
        putenv('DOCBEE_TOKEN');
        $_ENV['DOCBEE_TENANT'] = 'envcompany';
        $_ENV['DOCBEE_TOKEN']  = 'envsecret';

        try {
            $config = DocbeeConfig::fromEnv();
            $this->assertSame('envcompany', $config->getTenant());
            $this->assertSame('envsecret', $config->getToken());
        } finally {
            unset($_ENV['DOCBEE_TENANT'], $_ENV['DOCBEE_TOKEN']);
            foreach ($backup as $key => $value) {
                if ($value !== null) {
                    $_ENV[$key] = $value;
                    putenv("{$key}={$value}");
                }
            }
        }
    }

    public function testFromArrayThrowsOnWhitespaceOnlyTenant(): void
    {
        $this->expectException(InvalidArgumentException::class);
        DocbeeConfig::fromArray(['tenant' => '   ', 'token' => 'tok']);
    }

    public function testFromArrayThrowsOnWhitespaceOnlyToken(): void
    {
        $this->expectException(InvalidArgumentException::class);
        DocbeeConfig::fromArray(['tenant' => 'mycompany', 'token' => '   ']);
    }

    public function testTrimsWhitespaceFromTenantAndToken(): void
    {
        $config = new DocbeeConfig(tenant: '  mycompany  ', token: '  secret  ');

        $this->assertSame('mycompany', $config->getTenant());
        $this->assertSame('secret', $config->getToken());
    }

    public function testThrowsOnTenantWithDotSeparator(): void
    {
        // Dots would allow URL injection like "evil.com" → https://evil.com.docbee.com
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/alphanumeric/');
        new DocbeeConfig(tenant: 'evil.com', token: 'tok');
    }

    public function testThrowsOnTenantWithSlash(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'my/company', token: 'tok');
    }

    public function testThrowsOnTenantWithAt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 'tenant@evil', token: 'tok');
    }

    public function testTenantAllowsHyphensAndUnderscores(): void
    {
        $config = new DocbeeConfig(tenant: 'my-company_123', token: 'tok');
        $this->assertSame('my-company_123', $config->getTenant());
    }

    public function testDebugInfoRedactsToken(): void
    {
        $config    = new DocbeeConfig(tenant: 'mycompany', token: 'supersecret');
        $debugInfo = $config->__debugInfo();

        $this->assertArrayHasKey('token', $debugInfo);
        $this->assertSame('***REDACTED***', $debugInfo['token']);
        $this->assertStringNotContainsString('supersecret', implode('', $debugInfo));
    }
}
