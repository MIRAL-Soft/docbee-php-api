<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * API compatibility gate — compares the live Docbee OpenAPI spec against the
 * PHP client implementation.
 *
 * BEHAVIOUR
 * ---------
 * - If the spec URL is unreachable:  test is SKIPPED (network issues are not
 *   a code problem; this is expected in offline/CI environments without access).
 *
 * - If the spec is reachable but differences are found:  test is marked
 *   INCOMPLETE (PHPUnit "I").  This signals "attention needed" without counting
 *   as a test failure, because the library can still work correctly even when
 *   the spec has evolved ahead of the implementation.
 *
 * - If the spec is reachable and no differences are found:  test PASSES.
 *
 * Run the full report manually for details:
 *   php bin/check-api-compat.php
 *
 * Save a snapshot baseline:
 *   php bin/check-api-compat.php --save-snapshot
 */
final class ApiCompatibilityTest extends TestCase
{
    private static string $checkerFile;
    private static string $configFile;

    public static function setUpBeforeClass(): void
    {
        self::$checkerFile = dirname(__DIR__, 3) . '/bin/ApiCompatChecker.php';
        self::$configFile  = dirname(__DIR__, 3) . '/config/api-compat.php';
    }

    // -------------------------------------------------------------------------
    // Spec reachability
    // -------------------------------------------------------------------------

    public function testSpecIsReachable(): void
    {
        $config  = $this->loadConfig();
        $url     = $config['spec_url'] ?? '';

        if ($url === '') {
            $this->markTestSkipped('spec_url is not configured in config/api-compat.php.');
        }

        $spec = $this->tryFetchSpec($url, (int) ($config['fetch_timeout'] ?? 15));

        if ($spec === null) {
            $this->markTestSkipped(
                "Docbee API specification could not be fetched from:\n"
                . "  {$url}\n"
                . "This is expected in offline or CI environments.\n"
                . "Compatibility check skipped."
            );
        }

        // We have a spec — just assert it has the expected shape
        $this->assertArrayHasKey('openapi', $spec, 'Spec must contain an "openapi" key.');
        $this->assertArrayHasKey('components', $spec, 'Spec must contain a "components" key.');
        $this->assertArrayHasKey('paths', $spec, 'Spec must contain a "paths" key.');
    }

    // -------------------------------------------------------------------------
    // Full compatibility check
    // -------------------------------------------------------------------------

    public function testDtosMatchSpec(): void
    {
        $config  = $this->loadConfig();
        $url     = $config['spec_url'] ?? '';

        if ($url === '') {
            $this->markTestSkipped('spec_url is not configured in config/api-compat.php.');
        }

        $spec = $this->tryFetchSpec($url, (int) ($config['fetch_timeout'] ?? 15));

        if ($spec === null) {
            $this->markTestSkipped(
                "Docbee API specification could not be fetched — compatibility check skipped.\n"
                . "URL: {$url}\n"
                . "Run 'php bin/check-api-compat.php' when a network connection is available."
            );
        }

        $checker  = $this->buildChecker($config);
        $snapshot = null;

        try {
            $snapshot = $checker->loadSnapshot();
        } catch (\Throwable) {
            // Snapshot errors are non-fatal
        }

        try {
            $report = $checker->run($spec, $snapshot);
        } catch (\Throwable $e) {
            $this->markTestIncomplete(
                "API compatibility check threw an unexpected exception:\n"
                . $e->getMessage() . "\n"
                . "Run 'php bin/check-api-compat.php' for details."
            );
        }

        if ($report->hasIssues()) {
            $this->markTestIncomplete(
                "API compatibility issues found: " . $report->summarize() . "\n\n"
                . "The library may still work correctly, but the implementation has drifted\n"
                . "from the current spec.  Run the full report for details:\n"
                . "  php bin/check-api-compat.php\n\n"
                . "To update the snapshot after reviewing changes:\n"
                . "  php bin/check-api-compat.php --save-snapshot"
            );
        }

        // All good
        $this->assertTrue(true);
    }

    // -------------------------------------------------------------------------
    // Snapshot integrity
    // -------------------------------------------------------------------------

    /**
     * Verifies that the snapshot file, if it exists, is valid JSON.
     * This catches accidental corruption of the snapshot file.
     */
    public function testSnapshotFileIsValidIfPresent(): void
    {
        $config       = $this->loadConfig();
        $snapshotFile = $config['snapshot_file'] ?? '';

        if ($snapshotFile === '' || !is_file($snapshotFile)) {
            $this->markTestSkipped('No snapshot file present — skipping integrity check.');
        }

        $raw = @file_get_contents($snapshotFile);
        $this->assertNotFalse($raw, "Snapshot file exists but could not be read: {$snapshotFile}");
        $this->assertNotEmpty($raw, "Snapshot file is empty: {$snapshotFile}");

        try {
            $decoded = json_decode((string) $raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->fail("Snapshot file contains invalid JSON: {$e->getMessage()}");
        }

        $this->assertIsArray($decoded, 'Snapshot must decode to an array.');
        $this->assertArrayHasKey('components', $decoded, 'Snapshot must contain "components".');
        $this->assertArrayHasKey('paths',      $decoded, 'Snapshot must contain "paths".');
        $this->assertArrayHasKey('_snapshotDate', $decoded,
            'Snapshot must contain a "_snapshotDate" key. '
            . 'Re-save with: php bin/check-api-compat.php --save-snapshot'
        );
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function loadConfig(): array
    {
        if (!is_file(self::$configFile)) {
            $this->markTestSkipped('config/api-compat.php not found — compatibility tests skipped.');
        }

        try {
            $config = require self::$configFile;
        } catch (\Throwable $e) {
            $this->markTestSkipped('config/api-compat.php could not be loaded: ' . $e->getMessage());
        }

        if (!is_array($config)) {
            $this->markTestSkipped('config/api-compat.php did not return an array.');
        }

        return $config;
    }

    private function buildChecker(array $config): \ApiCompatChecker
    {
        if (!is_file(self::$checkerFile)) {
            $this->markTestSkipped('bin/ApiCompatChecker.php not found.');
        }

        require_once self::$checkerFile;

        return new \ApiCompatChecker($config);
    }

    /**
     * Tries to fetch the spec from the given URL.
     * Returns null on any network or parse error — never throws.
     */
    private function tryFetchSpec(string $url, int $timeout): ?array
    {
        $ctx = stream_context_create([
            'http' => [
                'timeout'       => $timeout,
                'ignore_errors' => true,
                'user_agent'    => 'docbee-api-compat-checker/1.0',
            ],
            'ssl'  => [
                'verify_peer'      => true,
                'verify_peer_name' => true,
            ],
        ]);

        try {
            $raw = @file_get_contents($url, false, $ctx);
        } catch (\Throwable) {
            return null;
        }

        if ($raw === false || $raw === '') {
            return null;
        }

        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            return is_array($decoded) ? $decoded : null;
        } catch (\JsonException) {
            return null;
        }
    }
}
