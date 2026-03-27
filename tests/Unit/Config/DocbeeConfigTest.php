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
        putenv('DOCBEE_TENANT');
        putenv('DOCBEE_TOKEN');

        $this->expectException(InvalidArgumentException::class);
        DocbeeConfig::fromEnv();
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

    public function testFromArrayAcceptsZeroStringAsTenant(): void
    {
        // '0' is a valid tenant name — empty() would incorrectly reject it
        $config = DocbeeConfig::fromArray(['tenant' => '0', 'token' => 'tok']);
        $this->assertSame('0', $config->getTenant());
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
