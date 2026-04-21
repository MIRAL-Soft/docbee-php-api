<?php

declare(strict_types=1);

/**
 * PHPUnit bootstrap file.
 *
 * Loads vendor autoload and — when present — reads tests/.env.test
 * so that integration tests can access real API credentials without
 * committing sensitive data to version control.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// ── Load tests/.env.test if it exists ────────────────────────────────────────
$envFile = __DIR__ . '/.env.test';

if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Skip comment lines
        if (str_starts_with(ltrim($line), '#')) {
            continue;
        }

        // Expect KEY=VALUE (value may contain '=' characters)
        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }

        $key   = trim(substr($line, 0, $pos));
        $value = trim(substr($line, $pos + 1));

        // Strip optional surrounding quotes (" or ')
        if (
            strlen($value) >= 2
            && (
                ($value[0] === '"'  && $value[-1] === '"')
                || ($value[0] === "'" && $value[-1] === "'")
            )
        ) {
            $value = substr($value, 1, -1);
        }

        if ($key !== '' && getenv($key) === false) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}
