#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Docbee API Compatibility Check — CLI entry point.
 *
 * Compares the live Docbee OpenAPI specification against the PHP client
 * implementation and prints a comprehensive compatibility report.
 *
 * Usage:
 *   php bin/check-api-compat.php [options]
 *
 * Options:
 *   --save-snapshot          Save the fetched spec as the new local snapshot.
 *                            Future runs will compare against this baseline
 *                            to detect API drift.
 *
 *   --output=<file>          Save the human-readable text report to <file>.
 *                            The report is always printed to stdout as well.
 *
 *   --json-output=<file>     Save a machine-readable JSON report to <file>.
 *
 *   --quiet                  Suppress stdout output (useful with --output).
 *
 *   --no-snapshot-compare    Skip the snapshot comparison even if a snapshot
 *                            exists (report only spec vs implementation).
 *
 * Exit codes:
 *   0  All checks passed (or spec was unreachable — check stdout for details).
 *   1  Compatibility issues were found.
 *   2  An unexpected error occurred.
 */

// ---------------------------------------------------------------------------
// Bootstrap
// ---------------------------------------------------------------------------

$baseDir = dirname(__DIR__);

if (!is_file($baseDir . '/vendor/autoload.php')) {
    fwrite(STDERR, "ERROR: vendor/autoload.php not found. Run 'composer install' first.\n");
    exit(2);
}

require $baseDir . '/vendor/autoload.php';
require __DIR__ . '/ApiCompatChecker.php';

$configFile = $baseDir . '/config/api-compat.php';
if (!is_file($configFile)) {
    fwrite(STDERR, "ERROR: config/api-compat.php not found.\n");
    exit(2);
}

$config = require $configFile;

// ---------------------------------------------------------------------------
// Parse CLI flags
// ---------------------------------------------------------------------------

$flags = parseCompatFlags($argv);

function parseCompatFlags(array $argv): array
{
    $flags = [];
    foreach (array_slice($argv, 1) as $arg) {
        if ($arg === '--save-snapshot') {
            $flags['save-snapshot'] = true;
        } elseif ($arg === '--quiet') {
            $flags['quiet'] = true;
        } elseif ($arg === '--no-snapshot-compare') {
            $flags['no-snapshot-compare'] = true;
        } elseif (str_starts_with($arg, '--output=')) {
            $flags['output'] = substr($arg, strlen('--output='));
        } elseif (str_starts_with($arg, '--json-output=')) {
            $flags['json-output'] = substr($arg, strlen('--json-output='));
        } elseif ($arg === '--help' || $arg === '-h') {
            $flags['help'] = true;
        }
    }
    return $flags;
}

if (isset($flags['help'])) {
    echo file_get_contents(__FILE__) !== false
        ? implode("\n", array_slice(
            array_filter(
                explode("\n", file_get_contents(__FILE__)),
                fn($l) => str_starts_with($l, ' * ') || str_starts_with($l, ' */'),
            ),
            0, 20,
        )) . "\n"
        : '';
    // Fall through to normal execution so the help text from the docblock is shown
    // Simply echo the top docblock
    $src   = file_get_contents(__FILE__);
    $start = strpos($src, '/**');
    $end   = strpos($src, ' */');
    if ($start !== false && $end !== false) {
        echo substr($src, $start + 3, $end - $start - 3);
    }
    exit(0);
}

$quiet             = isset($flags['quiet']);
$saveSnapshot      = isset($flags['save-snapshot']);
$skipSnapshotCmp   = isset($flags['no-snapshot-compare']);
$outputFile        = $flags['output']      ?? null;
$jsonOutputFile    = $flags['json-output'] ?? null;

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function printLine(string $line, bool $quiet): void
{
    if (!$quiet) {
        echo $line . PHP_EOL;
    }
}

function ensureDir(string $path): bool
{
    $dir = dirname($path);
    return is_dir($dir) || mkdir($dir, 0o755, true);
}

// ---------------------------------------------------------------------------
// Run
// ---------------------------------------------------------------------------

$checker = new ApiCompatChecker($config);

// 1. Fetch live spec
printLine('Fetching API specification from ' . ($config['spec_url'] ?? '(no URL)') . ' ...', $quiet);

$spec = null;
try {
    $spec = $checker->fetchSpec();
} catch (\Throwable $e) {
    fwrite(STDERR, "WARNING: Could not fetch spec: " . $e->getMessage() . "\n");
}

if ($spec === null) {
    printLine('', $quiet);
    printLine('WARNING: The Docbee API specification could not be fetched.', $quiet);
    printLine('         URL   : ' . ($config['spec_url'] ?? '(not configured)'), $quiet);
    printLine('         Reason: Network error, timeout, or invalid response.', $quiet);
    printLine('         Compatibility check could not be performed.', $quiet);
    printLine('', $quiet);
    exit(0); // Not an error — spec just wasn't reachable
}

printLine('  Spec fetched. Version: ' . ($spec['info']['version'] ?? 'unknown'), $quiet);

// 2. Load snapshot (for drift detection)
$snapshot = null;
if (!$skipSnapshotCmp) {
    try {
        $snapshot = $checker->loadSnapshot();
    } catch (\Throwable) {
        $snapshot = null;
    }

    if ($snapshot !== null) {
        printLine('  Snapshot loaded (' . ($snapshot['_snapshotDate'] ?? 'unknown date') . ').', $quiet);
    } else {
        printLine('  No snapshot found. Run with --save-snapshot to create a baseline.', $quiet);
    }
}

// 3. Run comparison
printLine('Running compatibility check...', $quiet);

try {
    $report = $checker->run($spec, $snapshot);
} catch (\Throwable $e) {
    fwrite(STDERR, "ERROR: Compatibility check failed unexpectedly: " . $e->getMessage() . "\n");
    fwrite(STDERR, $e->getTraceAsString() . "\n");
    exit(2);
}

// 4. Format reports
$textReport = formatTextReport($report);
$jsonReport = ($jsonOutputFile !== null) ? formatJsonReport($report) : null;

// 5. Output
if (!$quiet) {
    echo PHP_EOL . $textReport;
}

if ($outputFile !== null) {
    if (!ensureDir($outputFile)) {
        fwrite(STDERR, "WARNING: Could not create directory for output file '{$outputFile}'.\n");
    } else {
        $written = file_put_contents($outputFile, $textReport);
        if ($written === false) {
            fwrite(STDERR, "WARNING: Could not write text report to '{$outputFile}'.\n");
        } else {
            printLine("Text report saved to: {$outputFile}", $quiet);
        }
    }
}

if ($jsonOutputFile !== null && $jsonReport !== null) {
    if (!ensureDir($jsonOutputFile)) {
        fwrite(STDERR, "WARNING: Could not create directory for JSON output file '{$jsonOutputFile}'.\n");
    } else {
        $written = file_put_contents($jsonOutputFile, $jsonReport);
        if ($written === false) {
            fwrite(STDERR, "WARNING: Could not write JSON report to '{$jsonOutputFile}'.\n");
        } else {
            printLine("JSON report saved to: {$jsonOutputFile}", $quiet);
        }
    }
}

// 6. Save snapshot (AFTER running the report so the report uses the old snapshot for drift)
if ($saveSnapshot) {
    try {
        $saved = $checker->saveSnapshot($spec);
        if ($saved) {
            printLine('Snapshot updated: ' . ($config['snapshot_file'] ?? ''), $quiet);
        } else {
            fwrite(STDERR, "WARNING: Could not save snapshot to '" . ($config['snapshot_file'] ?? '') . "'.\n");
        }
    } catch (\Throwable $e) {
        fwrite(STDERR, "WARNING: Snapshot save failed: " . $e->getMessage() . "\n");
    }
}

// 7. Exit code
exit($report->hasIssues() ? 1 : 0);
