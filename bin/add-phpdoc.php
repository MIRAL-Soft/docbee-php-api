<?php

/**
 * Adds inline PHPDoc comments to all DTO constructor parameters
 * based on descriptions from the OpenAPI spec snapshot.
 *
 * Usage: php bin/add-phpdoc.php [--dry-run]
 */

declare(strict_types=1);

$dryRun = in_array('--dry-run', $argv ?? [], true);
$root   = dirname(__DIR__);

// Load the compat config for manual schema mappings
$config    = require $root . '/config/api-compat.php';
$schemaMap = $config['dto_schema_map'] ?? [];

// Load the pre-built field→description map
$fieldMap = json_decode(file_get_contents($root . '/config/spec_field_map.json'), true);

// --- helpers ---------------------------------------------------------------

function findSchemaName(string $dtoClass, array $schemaMap, array $fieldMap): ?string
{
    // 1. explicit override
    if (isset($schemaMap[$dtoClass])) {
        return $schemaMap[$dtoClass];
    }
    // 2. strip DTO suffix → exact match
    $bare = preg_replace('/DTO$/', '', $dtoClass);
    if (isset($fieldMap[$bare])) {
        return $bare;
    }
    // 3. case-insensitive
    foreach (array_keys($fieldMap) as $s) {
        if (strcasecmp($s, $bare) === 0) return $s;
    }
    return null;
}

function getDescriptions(string $dtoClass, array $schemaMap, array $fieldMap): array
{
    $schema = findSchemaName($dtoClass, $schemaMap, $fieldMap);
    if ($schema === null) return [];
    return $fieldMap[$schema] ?? [];
}

// Fallback descriptions for common cross-schema fields
$commonDescriptions = [
    'id'       => 'Unique identifier',
    'created'  => 'created date',
    'modified' => 'modified date',
    'link'     => 'REST API Link',
];

// --- process each DTO -------------------------------------------------------

$dtoDir = $root . '/src/DTO';
$files  = glob($dtoDir . '/*.php');
sort($files);

$changed = 0;
$skipped = 0;

foreach ($files as $file) {
    $basename = basename($file, '.php');
    if ($basename === 'AbstractDTO') {
        $skipped++;
        continue;
    }

    $source = file_get_contents($file);

    // Extract class name from file
    if (!preg_match('/class\s+(\w+DTO)\b/', $source, $cm)) {
        $skipped++;
        continue;
    }
    $className = $cm[1];

    // Get descriptions for this DTO's schema
    $descriptions = getDescriptions($className, $schemaMap, $fieldMap);

    // Find the constructor block
    if (!preg_match('/public\s+function\s+__construct\s*\(/s', $source)) {
        $skipped++;
        continue;
    }

    // Process each constructor parameter line
    // Pattern: optional existing comment, then "private [readonly] ?Type $paramName"
    $modified = false;

    // Split into lines for processing
    $lines  = explode("\n", $source);
    $output = [];
    $inCtor = false;
    $depth  = 0;

    for ($i = 0; $i < count($lines); $i++) {
        $line = $lines[$i];

        // Detect constructor start
        if (!$inCtor && preg_match('/public\s+function\s+__construct\s*\(/', $line)) {
            $inCtor = true;
            $depth  = substr_count($line, '(') - substr_count($line, ')');
            $output[] = $line;
            continue;
        }

        if ($inCtor) {
            $depth += substr_count($line, '(') - substr_count($line, ')');
            if ($depth <= 0) {
                $inCtor = false;
                $output[] = $line;
                continue;
            }

            // Check if this line is a parameter line (contains $varName)
            $trimmed = trim($line);

            // Skip lines that are already comments
            if (str_starts_with($trimmed, '/**') || str_starts_with($trimmed, '*') || str_starts_with($trimmed, '//')) {
                $output[] = $line;
                continue;
            }

            // Check if PREVIOUS output line is already a /** */ comment
            $prevIdx = count($output) - 1;
            $prevTrimmed = trim($output[$prevIdx] ?? '');
            $prevIsComment = str_starts_with($prevTrimmed, '/**') || str_starts_with($prevTrimmed, '*');

            // Extract param name
            if (preg_match('/\$(\w+)\s*[,)]?\s*$/', $trimmed, $pm)) {
                $paramName = $pm[1];

                if (!$prevIsComment) {
                    // Look up description
                    $desc = $descriptions[$paramName]
                        ?? $commonDescriptions[$paramName]
                        ?? null;

                    if ($desc !== null) {
                        // Get indentation of current line
                        preg_match('/^(\s*)/', $line, $indent);
                        $ind = $indent[1];

                        // Clean up description: single line, no newlines
                        $desc = str_replace(["\n", "\r"], ' ', trim($desc));

                        $output[]  = $ind . '/** ' . $desc . ' */';
                        $modified  = true;
                    }
                }
            }

            $output[] = $line;
            continue;
        }

        $output[] = $line;
    }

    if ($modified) {
        $newSource = implode("\n", $output);
        if (!$dryRun) {
            file_put_contents($file, $newSource);
        }
        echo ($dryRun ? '[DRY] ' : '') . "Updated: {$basename}\n";
        $changed++;
    } else {
        $skipped++;
    }
}

echo "\nDone. Changed: {$changed}, Skipped: {$skipped}\n";
