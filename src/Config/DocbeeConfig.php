<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Config;

use InvalidArgumentException;

/**
 * Immutable configuration object for the Docbee API client.
 *
 * This is the single source of truth for all connection parameters.
 * Create one instance and pass it to {@see \miralsoft\docbee\api\Client\DocbeeClient}.
 *
 * ```php
 * // Direct construction
 * $config = new DocbeeConfig(tenant: 'mycompany', token: 'abc123');
 *
 * // From environment variables (DOCBEE_TENANT, DOCBEE_TOKEN)
 * $config = DocbeeConfig::fromEnv();
 *
 * // From an associative array (e.g. loaded from a config file)
 * $config = DocbeeConfig::fromArray(['tenant' => 'mycompany', 'token' => 'abc123']);
 * ```
 */
final class DocbeeConfig
{
    /** Default request timeout in seconds. */
    private const int DEFAULT_TIMEOUT = 30;

    /** Default connection timeout in seconds. */
    private const int DEFAULT_CONNECT_TIMEOUT = 10;

    /** Default maximum number of retry attempts for 429 / 5xx responses. */
    private const int DEFAULT_MAX_RETRIES = 3;

    /** Base URL template; {tenant} is replaced at runtime. */
    private const string BASE_URL_TEMPLATE = 'https://%s.docbee.com/restApi/v1/';

    /**
     * Allowed characters for the tenant subdomain.
     * Prevents URL-injection via a crafted value such as "evil.com/path".
     */
    private const string TENANT_PATTERN = '/^[a-zA-Z0-9][a-zA-Z0-9_-]*$/';

    // Declared separately (not constructor-promoted) so we can trim+validate
    // before assigning to the readonly property.
    private readonly string $tenant;
    private readonly string $token;

    public function __construct(
        string $tenant,
        string $token,
        private readonly int $timeout        = self::DEFAULT_TIMEOUT,
        private readonly int $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT,
        private readonly int $maxRetries     = self::DEFAULT_MAX_RETRIES,
    ) {
        $tenant = trim($tenant);
        $token  = trim($token);

        if ($tenant === '') {
            throw new InvalidArgumentException('DocbeeConfig: tenant must not be empty.');
        }
        if (!preg_match(self::TENANT_PATTERN, $tenant)) {
            throw new InvalidArgumentException(
                'DocbeeConfig: tenant must contain only alphanumeric characters, hyphens, and underscores.'
            );
        }
        if ($token === '') {
            throw new InvalidArgumentException('DocbeeConfig: token must not be empty.');
        }
        if ($timeout <= 0) {
            throw new InvalidArgumentException('DocbeeConfig: timeout must be greater than 0.');
        }
        if ($connectTimeout <= 0) {
            throw new InvalidArgumentException('DocbeeConfig: connectTimeout must be greater than 0.');
        }

        $this->tenant = $tenant;
        $this->token  = $token;
    }

    // -------------------------------------------------------------------------
    // Factory methods
    // -------------------------------------------------------------------------

    /**
     * Creates a config instance from environment variables.
     *
     * Required env vars:
     *  - DOCBEE_TENANT  – your Docbee subdomain
     *  - DOCBEE_TOKEN   – your API token
     *
     * Optional env vars:
     *  - DOCBEE_TIMEOUT         – request timeout in seconds (default: 30)
     *  - DOCBEE_CONNECT_TIMEOUT – connection timeout in seconds (default: 10)
     *  - DOCBEE_MAX_RETRIES     – max retry attempts (default: 3)
     *
     * @throws InvalidArgumentException if required variables are missing.
     */
    public static function fromEnv(): self
    {
        $tenant = getenv('DOCBEE_TENANT') ?: '';
        $token  = getenv('DOCBEE_TOKEN')  ?: '';

        if ($tenant === '') {
            throw new InvalidArgumentException('Environment variable DOCBEE_TENANT is not set.');
        }
        if ($token === '') {
            throw new InvalidArgumentException('Environment variable DOCBEE_TOKEN is not set.');
        }

        return new self(
            tenant:         $tenant,
            token:          $token,
            timeout:        (int) (getenv('DOCBEE_TIMEOUT')         ?: self::DEFAULT_TIMEOUT),
            connectTimeout: (int) (getenv('DOCBEE_CONNECT_TIMEOUT') ?: self::DEFAULT_CONNECT_TIMEOUT),
            maxRetries:     (int) (getenv('DOCBEE_MAX_RETRIES')     ?: self::DEFAULT_MAX_RETRIES),
        );
    }

    /**
     * Creates a config instance from an associative array.
     *
     * Required keys: `tenant`, `token`.
     * Optional keys: `timeout`, `connect_timeout`, `max_retries`.
     *
     * @param array<string, mixed> $data
     * @throws InvalidArgumentException if required keys are missing.
     */
    public static function fromArray(array $data): self
    {
        // Use isset() + trim() instead of empty() so that the string '0' is accepted as
        // a valid tenant or token (empty() would incorrectly treat '0' as falsy).
        if (!isset($data['tenant']) || trim((string) $data['tenant']) === '') {
            throw new InvalidArgumentException("DocbeeConfig::fromArray() requires key 'tenant'.");
        }
        if (!isset($data['token']) || trim((string) $data['token']) === '') {
            throw new InvalidArgumentException("DocbeeConfig::fromArray() requires key 'token'.");
        }

        return new self(
            tenant:         (string) $data['tenant'],
            token:          (string) $data['token'],
            timeout:        (int)    ($data['timeout']         ?? self::DEFAULT_TIMEOUT),
            connectTimeout: (int)    ($data['connect_timeout'] ?? self::DEFAULT_CONNECT_TIMEOUT),
            maxRetries:     (int)    ($data['max_retries']     ?? self::DEFAULT_MAX_RETRIES),
        );
    }

    // -------------------------------------------------------------------------
    // Getters
    // -------------------------------------------------------------------------

    /** Returns the Docbee tenant subdomain. */
    public function getTenant(): string
    {
        return $this->tenant;
    }

    /** Returns the API bearer token. */
    public function getToken(): string
    {
        return $this->token;
    }

    /** Returns the full base URL for the API (e.g. https://mycompany.docbee.com/restApi/v1/). */
    public function getBaseUrl(): string
    {
        return sprintf(self::BASE_URL_TEMPLATE, $this->tenant);
    }

    /** Returns the request timeout in seconds. */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /** Returns the connection timeout in seconds. */
    public function getConnectTimeout(): int
    {
        return $this->connectTimeout;
    }

    /** Returns the maximum number of retry attempts. */
    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    /**
     * Prevents accidental token exposure when var_dump() or print_r() is called.
     *
     * @return array<string, string>
     */
    public function __debugInfo(): array
    {
        return [
            'tenant'         => $this->tenant,
            'token'          => '***REDACTED***',
            'baseUrl'        => $this->getBaseUrl(),
            'timeout'        => (string) $this->timeout,
            'connectTimeout' => (string) $this->connectTimeout,
            'maxRetries'     => (string) $this->maxRetries,
        ];
    }
}
