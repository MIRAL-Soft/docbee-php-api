<?php

declare(strict_types=1);

/**
 * Template for ad-hoc live probes against a Docbee tenant.
 *
 * Credentials are loaded from tests/.env.test (DOCBEE_TENANT / DOCBEE_TOKEN) —
 * the same file the integration test suite uses. NEVER hard-code tokens in
 * probe scripts: everything in scripts/ except this template is gitignored,
 * but tokens on disk are still tokens.
 *
 * Usage:
 *   1. Copy this file:  cp scripts/probe-template.php scripts/probe_mytopic.php
 *   2. Write your probe code below.
 *   3. Run it:          php scripts/probe_mytopic.php
 *   4. Delete it when the finding is documented (CHANGELOG/README/docblock).
 */

require_once __DIR__ . '/../vendor/autoload.php';

use miralsoft\docbee\api\Client\DocbeeClient;
use miralsoft\docbee\api\Config\DocbeeConfig;

// ── Load tests/.env.test (same loader as tests/bootstrap.php) ────────────────
$envFile = __DIR__ . '/../tests/.env.test';
if (!is_file($envFile)) {
    fwrite(STDERR, "tests/.env.test not found — create it with DOCBEE_TENANT and DOCBEE_TOKEN.\n");
    exit(1);
}
foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with(ltrim($line), '#') || !str_contains($line, '=')) {
        continue;
    }
    [$key, $value] = explode('=', $line, 2);
    $key   = trim($key);
    $value = trim($value, " \t\"'");
    if ($key !== '' && getenv($key) === false) {
        putenv("{$key}={$value}");
    }
}

$client = new DocbeeClient(DocbeeConfig::fromEnv());

// ── Probe code goes here ──────────────────────────────────────────────────────

$me = $client->users()->me();
echo "Connected as: {$me->getId()}\n";
