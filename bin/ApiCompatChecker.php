<?php

declare(strict_types=1);

/**
 * Docbee API Compatibility Checker
 *
 * Compares the live Docbee OpenAPI specification against the PHP client
 * implementation and reports every discrepancy:
 *
 *  - DTO fields missing from the implementation (spec → DTO)
 *  - DTO fields that no longer exist in the spec (potentially deprecated)
 *  - read-only mismatches between spec and DTO properties
 *  - type mismatches
 *  - nested object/array fields stored as flat scalars
 *  - API spec changes since the last saved snapshot (drift detection)
 *  - API paths with no corresponding Resource class
 *
 * This file is NOT autoloaded.  Require it explicitly:
 *   require __DIR__ . '/../bin/ApiCompatChecker.php';
 */

// ---------------------------------------------------------------------------
// Value objects
// ---------------------------------------------------------------------------

final class FieldInfo
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $specType,
        public readonly bool $specReadOnly,
        public readonly bool $specDeprecated,
        public readonly bool $specNullable,
        public readonly ?string $specRef,        // original $ref target, if any
        public readonly bool $isNestedPath,      // e.g. "address.city"
    ) {}
}

final class DtoFieldInfo
{
    public function __construct(
        public readonly string $name,
        public readonly string $phpType,         // e.g. "int", "string", "array"
        public readonly bool $phpNullable,
        public readonly bool $phpReadOnly,
        public readonly ?string $nestedDtoClass = null,  // e.g. "WorkLogDTO" from @var WorkLogDTO[]|null
    ) {}
}

final class DtoReport
{
    /** @var list<FieldInfo> */
    public array $missingFields   = [];   // in spec, not in DTO
    /** @var list<DtoFieldInfo> */
    public array $extraFields     = [];   // in DTO, not in spec
    /** @var list<array{field:string, specReadOnly:bool, dtoReadOnly:bool}> */
    public array $readonlyMismatches = [];
    /** @var list<array{field:string, specType:string, dtoType:string}> */
    public array $typeMismatches  = [];
    /** @var list<FieldInfo> */
    public array $nestedObjects   = [];   // complex spec fields stored flat in DTO (untyped ?array)
    /** @var list<FieldInfo> */
    public array $deprecatedFields = [];  // fields the spec marks as deprecated
    /** @var array<string, array{dtoClass:string, schemaName:string, report:DtoReport}> keyed by field name */
    public array $nestedDtoResults = [];  // results of recursive checks on typed nested DTOs
    public ?string $specSchemaName = null;
    public bool $schemaFound       = false;
}

final class EndpointCoverage
{
    /** @var list<string> */
    public array $implemented = [];
    /** @var list<string> kept for backward compatibility — union of all three missing categories */
    public array $notImplemented = [];

    /** @var list<array{path:string, methods:list<string>}> No Resource class for this top-level entity */
    public array $missingResources = [];
    /** @var list<array{path:string, methods:list<string>}> Parent resource exists but sub-path not implemented */
    public array $missingSubResources = [];
    /** @var list<array{path:string, methods:list<string>}> Special action/operation on an existing resource */
    public array $missingActions = [];
}

final class ApiChange
{
    /** @var list<string> */
    public array $schemasAdded = [];
    /** @var list<string> */
    public array $schemasRemoved = [];
    /** @var array<string, array{added:list<string>, removed:list<string>, typeChanges:list<string>}> */
    public array $schemasModified = [];
    /** @var list<string> */
    public array $pathsAdded = [];
    /** @var list<string> */
    public array $pathsRemoved = [];
    /** @var list<string> */
    public array $operationsDeprecated = [];
}

final class CompatReport
{
    /** @var array<string, DtoReport> keyed by short DTO class name */
    public array $dtoReports = [];
    /** @var list<string> */
    public array $dtosMissingSchema = [];
    /** @var list<string> */
    public array $schemasMissingDto = [];
    public EndpointCoverage $endpointCoverage;
    public ?ApiChange $apiChange = null;

    public string $specUrl       = '';
    public ?string $specVersion  = null;
    public ?string $specTitle    = null;
    public string $generatedAt   = '';
    public ?string $snapshotDate = null;

    public function __construct()
    {
        $this->endpointCoverage = new EndpointCoverage();
        $this->generatedAt      = gmdate('Y-m-d H:i:s') . ' UTC';
    }

    public function hasIssues(): bool
    {
        foreach ($this->dtoReports as $r) {
            if (!empty($r->missingFields) || !empty($r->extraFields)
                || !empty($r->readonlyMismatches) || !empty($r->typeMismatches)) {
                return true;
            }
            // Also check nested DTO results
            foreach ($r->nestedDtoResults as $nested) {
                $nr = $nested['report'];
                if (!empty($nr->missingFields) || !empty($nr->extraFields)
                    || !empty($nr->readonlyMismatches) || !empty($nr->typeMismatches)) {
                    return true;
                }
            }
        }
        return !empty($this->dtosMissingSchema)
            || !empty($this->schemasMissingDto)
            || !empty($this->endpointCoverage->notImplemented)
            || ($this->apiChange !== null && $this->apiChange->schemasAdded !== []);
    }

    public function summarize(): string
    {
        $counts = [
            'DTOs without schema'                    => count($this->dtosMissingSchema),
            'Schemas without DTO'                    => count($this->schemasMissingDto),
            'Unimplemented endpoints (total)'        => count($this->endpointCoverage->notImplemented),
            '  · Missing resources'                  => count($this->endpointCoverage->missingResources),
            '  · Missing sub-resources'              => count($this->endpointCoverage->missingSubResources),
            '  · Missing actions/operations'         => count($this->endpointCoverage->missingActions),
        ];
        $fieldIssues = 0;
        foreach ($this->dtoReports as $r) {
            $fieldIssues += count($r->missingFields) + count($r->extraFields)
                + count($r->readonlyMismatches) + count($r->typeMismatches);
            // Count nested DTO issues too
            foreach ($r->nestedDtoResults as $nested) {
                $nr = $nested['report'];
                $fieldIssues += count($nr->missingFields) + count($nr->extraFields)
                    + count($nr->readonlyMismatches) + count($nr->typeMismatches);
            }
        }
        $counts['Field mismatches'] = $fieldIssues;

        $parts = [];
        foreach ($counts as $label => $n) {
            if ($n > 0) {
                $parts[] = "{$label}: {$n}";
            }
        }
        return $parts === [] ? 'No issues found.' : implode(', ', $parts);
    }
}

// ---------------------------------------------------------------------------
// Main checker
// ---------------------------------------------------------------------------

final class ApiCompatChecker
{
    private readonly string $specUrl;
    private readonly string $snapshotFile;
    private readonly int    $fetchTimeout;
    private readonly string $dtoPath;
    private readonly string $dtoNamespace;
    private readonly string $resourcePath;
    private readonly string $resourceNamespace;
    /** @var list<string> */
    private readonly array  $dtoSkip;
    /** @var array<string,string> short DTO name → spec schema name overrides */
    private readonly array  $dtoSchemaMap;

    public function __construct(array $config)
    {
        $this->specUrl           = (string) ($config['spec_url']        ?? '');
        $this->snapshotFile      = (string) ($config['snapshot_file']   ?? '');
        $this->fetchTimeout      = (int)    ($config['fetch_timeout']   ?? 15);
        $this->dtoPath           = rtrim((string) ($config['dto_path']           ?? ''), '/\\');
        $this->dtoNamespace      = (string) ($config['dto_namespace']    ?? '');
        $this->resourcePath      = rtrim((string) ($config['resource_path']      ?? ''), '/\\');
        $this->resourceNamespace = (string) ($config['resource_namespace'] ?? '');
        $this->dtoSkip           = (array)  ($config['dto_skip']        ?? []);
        $this->dtoSchemaMap      = (array)  ($config['dto_schema_map']  ?? []);
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Fetches the OpenAPI spec from the configured URL.
     * Returns null on any network or parse error.
     */
    public function fetchSpec(): ?array
    {
        if ($this->specUrl === '') {
            return null;
        }

        $ctx = stream_context_create([
            'http' => [
                'timeout'        => $this->fetchTimeout,
                'ignore_errors'  => true,
                'user_agent'     => 'docbee-api-compat-checker/1.0',
            ],
            'ssl'  => [
                'verify_peer'      => true,
                'verify_peer_name' => true,
            ],
        ]);

        try {
            $raw = @file_get_contents($this->specUrl, false, $ctx);
        } catch (\Throwable) {
            return null;
        }

        if ($raw === false || $raw === '') {
            return null;
        }

        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Loads the local spec snapshot.  Returns null if no snapshot exists yet.
     */
    public function loadSnapshot(): ?array
    {
        if ($this->snapshotFile === '' || !is_file($this->snapshotFile)) {
            return null;
        }

        try {
            $raw     = file_get_contents($this->snapshotFile);
            $decoded = json_decode((string) $raw, true, 512, JSON_THROW_ON_ERROR);
            return is_array($decoded) ? $decoded : null;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Saves the given spec as the new snapshot.
     * Adds a `_snapshotDate` meta-key for future reference.
     */
    public function saveSnapshot(array $spec): bool
    {
        if ($this->snapshotFile === '') {
            return false;
        }

        $spec['_snapshotDate'] = gmdate('Y-m-d H:i:s') . ' UTC';

        $dir = dirname($this->snapshotFile);
        if (!is_dir($dir) && !mkdir($dir, 0o755, true)) {
            return false;
        }

        try {
            $json = json_encode($spec, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            return file_put_contents($this->snapshotFile, $json) !== false;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Runs the full compatibility check and returns a report.
     *
     * @param array  $spec     Live (or pre-loaded) OpenAPI spec.
     * @param ?array $snapshot Previously saved snapshot, or null to skip drift detection.
     */
    public function run(array $spec, ?array $snapshot = null): CompatReport
    {
        $report = new CompatReport();
        $report->specUrl     = $this->specUrl;
        $report->specVersion = $spec['info']['version'] ?? null;
        $report->specTitle   = $spec['info']['title']   ?? null;
        $report->snapshotDate = $snapshot['_snapshotDate'] ?? null;

        // 1. API drift detection (spec vs snapshot)
        if ($snapshot !== null) {
            $report->apiChange = $this->compareSpecVsSnapshot($spec, $snapshot);
        }

        // 2. DTO vs spec schema comparison
        $specSchemas = $this->getAllSpecSchemas($spec);
        $dtoClasses  = $this->discoverDtoClasses();

        foreach ($dtoClasses as $shortName => $fullClass) {
            $dtoReport = new DtoReport();

            // Attempt to find a matching spec schema
            $schemaName = $this->findSchemaName($shortName, array_keys($specSchemas));
            $dtoReport->specSchemaName = $schemaName;
            $dtoReport->schemaFound    = $schemaName !== null;

            if ($schemaName === null) {
                $report->dtosMissingSchema[] = $shortName;
                $report->dtoReports[$shortName] = $dtoReport;
                continue;
            }

            // Extract all spec fields (recursively)
            $visited   = [];
            $specFields = $this->extractFields($spec, $specSchemas[$schemaName], $visited, '');

            // Extract DTO fields via reflection
            $dtoFields = $this->getDtoFields($fullClass);

            // Compare
            $this->compareDtoVsSpec($specFields, $dtoFields, $dtoReport, $spec, $specSchemas);

            $report->dtoReports[$shortName] = $dtoReport;
        }

        // 3. Schemas in spec without a DTO
        $dtoShortNames = array_keys($dtoClasses);
        foreach (array_keys($specSchemas) as $schemaName) {
            // Skip helper/input-only schemas (New*, Update*)
            if ($this->isInputOnlySchema($schemaName)) {
                continue;
            }
            $matched = false;
            foreach ($dtoShortNames as $dtoShort) {
                if ($this->findSchemaName($dtoShort, [$schemaName]) !== null) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $report->schemasMissingDto[] = $schemaName;
            }
        }

        // 4. Endpoint coverage
        $report->endpointCoverage = $this->checkEndpointCoverage($spec);

        return $report;
    }

    // -------------------------------------------------------------------------
    // Spec schema resolution
    // -------------------------------------------------------------------------

    /**
     * Returns all top-level schemas from components.schemas as
     * ['SchemaName' => schemaArray, ...].
     *
     * @return array<string, array>
     */
    private function getAllSpecSchemas(array $spec): array
    {
        return $spec['components']['schemas'] ?? [];
    }

    /**
     * Resolves a JSON Pointer $ref (e.g. "#/components/schemas/Customer")
     * within the spec.  Returns null on invalid refs or circular loops.
     */
    private function resolveRef(array $spec, string $ref, array &$visited): ?array
    {
        if (isset($visited[$ref])) {
            return null; // circular reference guard
        }
        $visited[$ref] = true;

        $pointer = ltrim($ref, '#/');
        $parts   = explode('/', $pointer);
        $node    = $spec;

        foreach ($parts as $part) {
            $part = str_replace(['~1', '~0'], ['/', '~'], $part); // JSON Pointer unescape
            if (!is_array($node) || !array_key_exists($part, $node)) {
                return null;
            }
            $node = $node[$part];
        }

        return is_array($node) ? $node : null;
    }

    /**
     * Recursively extracts all field names (with dot-notation paths for nested
     * objects/arrays) from an OpenAPI schema definition.
     *
     * Returns an array of FieldInfo objects keyed by their path.
     *
     * @param  array  $spec    Full OpenAPI spec (needed for $ref resolution).
     * @param  array  $schema  The schema node to process.
     * @param  array  &$visited Circular-reference guard (ref strings).
     * @param  string $prefix  Dot-notation path prefix for nested fields.
     * @return array<string, FieldInfo>
     */
    private function extractFields(array $spec, array $schema, array &$visited, string $prefix): array
    {
        // Resolve $ref first
        if (isset($schema['$ref'])) {
            $resolved = $this->resolveRef($spec, $schema['$ref'], $visited);
            if ($resolved === null) {
                return [];
            }
            $schema = $resolved;
        }

        $fields = [];

        // allOf: merge fields from all sub-schemas
        if (isset($schema['allOf']) && is_array($schema['allOf'])) {
            foreach ($schema['allOf'] as $sub) {
                if (!is_array($sub)) {
                    continue;
                }
                $subVisited = $visited;
                $fields     = array_merge($fields, $this->extractFields($spec, $sub, $subVisited, $prefix));
            }
        }

        // oneOf / anyOf: list all variants as potential fields (informational)
        foreach (['oneOf', 'anyOf'] as $combiner) {
            if (isset($schema[$combiner]) && is_array($schema[$combiner])) {
                foreach ($schema[$combiner] as $sub) {
                    if (!is_array($sub)) {
                        continue;
                    }
                    $subVisited = $visited;
                    $fields     = array_merge($fields, $this->extractFields($spec, $sub, $subVisited, $prefix));
                }
            }
        }

        // Direct properties
        if (isset($schema['properties']) && is_array($schema['properties'])) {
            foreach ($schema['properties'] as $fieldName => $fieldSchema) {
                if (!is_array($fieldSchema)) {
                    continue;
                }

                $fieldPath = $prefix !== '' ? "{$prefix}.{$fieldName}" : $fieldName;

                // Resolve $ref on the field itself
                $resolvedField = $fieldSchema;
                $refTarget     = null;
                if (isset($fieldSchema['$ref'])) {
                    $refTarget     = $fieldSchema['$ref'];
                    $subVisited    = $visited;
                    $resolvedField = $this->resolveRef($spec, $refTarget, $subVisited) ?? $fieldSchema;
                }

                $specType    = $resolvedField['type'] ?? (isset($resolvedField['$ref']) ? 'object' : null);
                $isReadOnly  = (bool) ($fieldSchema['readOnly']   ?? $resolvedField['readOnly']   ?? false);
                $isDeprecated = (bool) ($fieldSchema['deprecated'] ?? $resolvedField['deprecated'] ?? false);
                $isNullable  = (bool) ($fieldSchema['nullable']   ?? $resolvedField['nullable']   ?? false);

                $fields[$fieldPath] = new FieldInfo(
                    name:         $fieldPath,
                    specType:     $specType,
                    specReadOnly: $isReadOnly,
                    specDeprecated: $isDeprecated,
                    specNullable: $isNullable,
                    specRef:      $refTarget,
                    isNestedPath: $prefix !== '',
                );

                // Recurse into object properties
                if ($specType === 'object' && isset($resolvedField['properties'])) {
                    $subVisited = $visited;
                    $nested     = $this->extractFields($spec, $resolvedField, $subVisited, $fieldPath);
                    $fields     = array_merge($fields, $nested);
                }

                // Recurse into array items
                if ($specType === 'array' && isset($fieldSchema['items']) && is_array($fieldSchema['items'])) {
                    $items       = $fieldSchema['items'];
                    $itemPath    = "{$fieldPath}[]";
                    $subVisited  = $visited;

                    $resolvedItems = $items;
                    if (isset($items['$ref'])) {
                        $resolvedItems = $this->resolveRef($spec, $items['$ref'], $subVisited) ?? $items;
                    }

                    if (isset($resolvedItems['properties'])) {
                        $nested  = $this->extractFields($spec, $resolvedItems, $subVisited, $itemPath);
                        $fields  = array_merge($fields, $nested);
                    } elseif (isset($resolvedItems['allOf'])) {
                        $nested  = $this->extractFields($spec, $resolvedItems, $subVisited, $itemPath);
                        $fields  = array_merge($fields, $nested);
                    }
                }
            }
        }

        return $fields;
    }

    // -------------------------------------------------------------------------
    // DTO class discovery & reflection
    // -------------------------------------------------------------------------

    /**
     * Scans the DTO directory and returns ['ShortName' => 'Full\\Class\\Name', ...].
     * Skips abstract classes and classes listed in dto_skip.
     *
     * @return array<string, string>
     */
    private function discoverDtoClasses(): array
    {
        if (!is_dir($this->dtoPath)) {
            return [];
        }

        $classes = [];
        foreach (new \DirectoryIterator($this->dtoPath) as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $shortName = $file->getBasename('.php');
            if (in_array($shortName, $this->dtoSkip, true)) {
                continue;
            }

            $fullClass = $this->dtoNamespace . $shortName;

            try {
                if (!class_exists($fullClass)) {
                    continue;
                }
                $rc = new \ReflectionClass($fullClass);
                if ($rc->isAbstract()) {
                    continue;
                }
            } catch (\Throwable) {
                continue;
            }

            $classes[$shortName] = $fullClass;
        }

        ksort($classes);
        return $classes;
    }

    /**
     * Extracts property information from a DTO class via reflection.
     *
     * Also parses constructor parameter docblocks to detect typed nested DTO arrays,
     * e.g. `/** @var WorkLogDTO[]|null ... *\/` → nestedDtoClass = "WorkLogDTO".
     *
     * @return array<string, DtoFieldInfo>
     */
    private function getDtoFields(string $fullClass): array
    {
        $fields = [];

        try {
            $rc = new \ReflectionClass($fullClass);

            // Build a map of parameter name → docblock from the constructor,
            // since promoted constructor parameters carry their docblock on the
            // parameter, not on the property itself.
            $constructorParamDocs = [];
            $constructor = $rc->getConstructor();
            if ($constructor !== null) {
                // Parse the raw constructor source for inline docblocks on parameters.
                // ReflectionParameter does not expose docComments, so we read the source.
                $fileName  = $rc->getFileName();
                $startLine = $constructor->getStartLine();
                $endLine   = $constructor->getEndLine();

                if ($fileName !== false && $startLine !== false && $endLine !== false) {
                    $allLines = file($fileName);
                    if ($allLines !== false) {
                        // Extract only the lines of the constructor signature
                        $ctorLines = array_slice($allLines, $startLine - 1, $endLine - $startLine + 1);
                        $ctorSrc   = implode('', $ctorLines);

                        // Match patterns like:
                        //   /** @var WorkLogDTO[]|null some comment */
                        //   private ?array $workLogs,
                        // We find each /** ... */ block that is immediately followed by a
                        // property declaration ($varName).
                        if (preg_match_all(
                            '/\/\*\*\s*(.*?)\s*\*\/\s*(?:private|protected|public|readonly|\s)*\s*\?\s*array\s+\$(\w+)/s',
                            $ctorSrc,
                            $matches,
                            PREG_SET_ORDER,
                        )) {
                            foreach ($matches as $m) {
                                $docContent = $m[1];
                                $paramName  = $m[2];
                                // Look for @var SomeDTO[]
                                if (preg_match('/@var\s+(\w+DTO)\[\]/', $docContent, $varMatch)) {
                                    $constructorParamDocs[$paramName] = $varMatch[1];
                                }
                            }
                        }
                    }
                }
            }

            foreach ($rc->getProperties() as $prop) {
                // Only properties declared directly on this class
                if ($prop->getDeclaringClass()->getName() !== $fullClass) {
                    continue;
                }

                $type     = $prop->getType();
                $phpType  = 'mixed';
                $nullable = true;

                if ($type instanceof \ReflectionNamedType) {
                    $phpType  = $type->getName();
                    $nullable = $type->allowsNull();
                } elseif ($type instanceof \ReflectionUnionType) {
                    $names   = array_map(fn($t) => $t->getName(), $type->getTypes());
                    $phpType = implode('|', $names);
                    $nullable = in_array('null', $names, true);
                }

                // Detect nested DTO class from constructor docblock
                $nestedDtoClass = null;
                if ($phpType === 'array' && isset($constructorParamDocs[$prop->getName()])) {
                    $nestedDtoClass = $constructorParamDocs[$prop->getName()];
                }

                $fields[$prop->getName()] = new DtoFieldInfo(
                    name:           $prop->getName(),
                    phpType:        $phpType,
                    phpNullable:    $nullable,
                    phpReadOnly:    $prop->isReadOnly(),
                    nestedDtoClass: $nestedDtoClass,
                );
            }
        } catch (\Throwable) {
            // Reflection failure — return empty; the caller will handle it
        }

        return $fields;
    }

    // -------------------------------------------------------------------------
    // Resource discovery
    // -------------------------------------------------------------------------

    /**
     * Scans the Resource directory and returns all non-abstract resource classes
     * with their endpoint strings.
     *
     * For top-level resources the endpoint is a default property value.
     * For sub-resources it is assigned in the constructor; we extract the template
     * by parsing the source file with a regex.
     *
     * @return array<string, array{class:string, endpoint:string}>
     */
    private function discoverResourceClasses(): array
    {
        if (!is_dir($this->resourcePath)) {
            return [];
        }

        $resources = [];
        foreach (new \DirectoryIterator($this->resourcePath) as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $shortName = $file->getBasename('.php');
            $fullClass = $this->resourceNamespace . $shortName;
            $filePath  = $file->getPathname();

            try {
                if (!class_exists($fullClass)) {
                    continue;
                }
                $rc = new \ReflectionClass($fullClass);
                if ($rc->isAbstract()) {
                    continue;
                }

                $defaults = $rc->getDefaultProperties();
                $endpoint = (string) ($defaults['endpoint'] ?? '');

                // Sub-resources set $this->endpoint dynamically in the constructor.
                // Fall back to source-code extraction.
                if ($endpoint === '') {
                    $endpoint = $this->extractEndpointFromSource($filePath);
                }

                // Note: standalone Resource classes (no $endpoint property, e.g. MessageResource)
                // are intentionally kept with endpoint = '' so that their method bodies can
                // still be scanned for explicit HTTP paths in checkEndpointCoverage().
            } catch (\Throwable) {
                continue;
            }

            $resources[$shortName] = [
                'class'    => $fullClass,
                'endpoint' => $endpoint,
                'filePath' => $filePath,
            ];
        }

        return $resources;
    }

    /**
     * Tries to extract an endpoint template from a PHP source file by matching
     * the pattern `$this->endpoint = "...";` in the constructor.
     *
     * Variable interpolations (e.g. `{$agreementId}`) are replaced with `*`.
     */
    private function extractEndpointFromSource(string $filePath): string
    {
        try {
            $src = @file_get_contents($filePath);
            if ($src === false) {
                return '';
            }

            // Match: $this->endpoint = "v1/some/{$var}/path";
            // or:    $this->endpoint = 'v1/some/path';
            if (preg_match('/\$this->endpoint\s*=\s*["\']([^"\']+)["\']/', $src, $m)) {
                // Replace PHP variable interpolations with wildcard
                $template = preg_replace('/\{\$[^}]+\}/', '*', $m[1]) ?? $m[1];
                return $template;
            }
        } catch (\Throwable) {
            // Ignore
        }

        return '';
    }

    /**
     * Scans a resource PHP source file for all direct `$this->http->*()` call
     * sites and returns the path strings used as the first argument.
     *
     * Two patterns are handled:
     *  - Double-quoted strings: `"{$this->endpoint}/foo/{$id}"`
     *    → `{$this->endpoint}` (and `{$this->base}`) are substituted with
     *      $baseEndpoint; all remaining `{$...}` interpolations become `*`.
     *  - Single-quoted literals: `'v1/message/sendMail'`
     *    → returned as-is.
     *
     * Paths that still contain unresolved PHP expressions after substitution
     * are silently skipped.
     *
     * @return list<string>
     */
    private function extractMethodEndpointsFromSource(string $filePath, string $baseEndpoint): array
    {
        try {
            $src = @file_get_contents($filePath);
            if ($src === false) {
                return [];
            }

            $paths = [];

            // Extract endpoint strings from constructor assignments, including ternary form:
            //   simple:  $this->endpoint = 'v1/foo';
            //   ternary: $this->endpoint = $x !== null ? "v1/foo/{$x}/bar" : 'v1/foo';
            // This ensures sub-resources with optional-constructor patterns are discovered.
            if (preg_match_all('/\$this->endpoint\s*=([^;]+);/', $src, $assignMatches) !== false) {
                foreach ($assignMatches[1] as $assignment) {
                    if (preg_match_all('/["\']([^"\']{4,})["\']/', $assignment, $strMatches) !== false) {
                        foreach ($strMatches[1] as $ep) {
                            if (!str_contains($ep, '/')) {
                                continue; // not a path string
                            }
                            $ep = preg_replace('/\{\$[^}]+\}/', '*', $ep) ?? $ep;
                            if (!str_contains($ep, '$')) {
                                $paths[] = $ep;
                            }
                        }
                    }
                }
            }

            // Double-quoted first argument: $this->http->get("{$this->endpoint}/foo/{$id}", ...)
            if (preg_match_all('/\$this->http->\w+\s*\(\s*"([^"]+)"/', $src, $matches) !== false) {
                foreach ($matches[1] as $path) {
                    // Resolve the most common self-referential placeholders
                    $path = str_replace(
                        ['{$this->endpoint}', '{$this->base}'],
                        $baseEndpoint,
                        $path
                    );
                    // Replace any remaining PHP variable interpolations with wildcard
                    $path = preg_replace('/\{\$[^}]+\}/', '*', $path) ?? $path;
                    // Skip if unresolved dollar-signs remain (complex dynamic construction)
                    if (str_contains($path, '$')) {
                        continue;
                    }
                    $paths[] = $path;
                }
            }

            // Single-quoted first argument (literal, no interpolation):
            // $this->http->post('v1/message/sendMail', $data)
            if (preg_match_all('/\$this->http->\w+\s*\(\s*\'([^\']+)\'/', $src, $matches) !== false) {
                foreach ($matches[1] as $path) {
                    $paths[] = $path;
                }
            }

            // Helper-method + concatenation:
            //   private function base(int $x): string { return "v1/proto/{$id}/group/{$x}"; }
            //   $this->http->get($this->base($x) . '/entries')
            //   $this->http->put($this->base($x) . "/{$idx}/mapping", $data)
            //
            // 1. Extract private helper methods that return a URL template.
            $helperMethods = [];
            // Note: [^}]{0,2000} – no /s flag needed (character classes match newlines by default);
            // the upper bound prevents excessive backtracking on pathological input.
            if (preg_match_all(
                '/private\s+function\s+(\w+)\s*\([^)]*\)\s*:\s*string\s*\{[^}]{0,2000}return\s*"([^"]+)"\s*;/',
                $src, $helperMatches, PREG_SET_ORDER
            ) !== false) {
                foreach ($helperMatches as $hm) {
                    $tpl = str_replace(['{$this->endpoint}', '{$this->base}'], $baseEndpoint, $hm[2]);
                    $tpl = preg_replace('/\{\$[^}]+\}/', '*', $tpl) ?? $tpl;
                    if (!str_contains($tpl, '$')) {
                        $helperMethods[$hm[1]] = $tpl;
                    }
                }
            }
            // 2. Combine helper return-template with the concatenated suffix from each HTTP call.
            foreach ($helperMethods as $helperName => $helperBase) {
                $hp = preg_quote($helperName, '/');
                // Single-quoted suffix: . '/entries'
                if (preg_match_all(
                    '/\$this->http->\w+\s*\(\s*\$this->' . $hp . '\s*\([^)]*\)\s*\.\s*\'([^\']+)\'/',
                    $src, $sfxMatches
                ) !== false) {
                    foreach ($sfxMatches[1] as $sfx) {
                        $paths[] = $helperBase . $sfx;
                    }
                }
                // Double-quoted suffix (may contain variables): . "/{$groupIdx}/entries"
                if (preg_match_all(
                    '/\$this->http->\w+\s*\(\s*\$this->' . $hp . '\s*\([^)]*\)\s*\.\s*"([^"]+)"/',
                    $src, $sfxMatches
                ) !== false) {
                    foreach ($sfxMatches[1] as $sfx) {
                        $sfx = preg_replace('/\{\$[^}]+\}/', '*', $sfx) ?? $sfx;
                        if (!str_contains($sfx, '$')) {
                            $paths[] = $helperBase . $sfx;
                        }
                    }
                }
            }

            return $paths;
        } catch (\Throwable) {
            return [];
        }
    }

    // -------------------------------------------------------------------------
    // Comparison logic
    // -------------------------------------------------------------------------

    /**
     * Tries to find the spec schema name that corresponds to a DTO short name.
     *
     * Strategy:
     *  1. Manual override from dto_schema_map config
     *  2. Exact match: "CustomerDTO" → "Customer"
     *  3. Case-insensitive match
     *  4. Last-word fallback for sub-resource DTOs:
     *     "AgreementComponentDTO" → try "Component" (last CamelCase word)
     *
     * @param  list<string> $schemaNames
     */
    private function findSchemaName(string $dtoShortName, array $schemaNames): ?string
    {
        // 1. Manual override
        if (isset($this->dtoSchemaMap[$dtoShortName])) {
            $mapped = $this->dtoSchemaMap[$dtoShortName];
            return in_array($mapped, $schemaNames, true) ? $mapped : null;
        }

        // Strip "DTO" suffix
        $baseName = preg_replace('/DTO$/', '', $dtoShortName) ?? $dtoShortName;

        // 2. Exact match
        if (in_array($baseName, $schemaNames, true)) {
            return $baseName;
        }

        // 3. Case-insensitive match
        $lower = strtolower($baseName);
        foreach ($schemaNames as $name) {
            if (strtolower($name) === $lower) {
                return $name;
            }
        }

        // 4. Last CamelCase word (sub-resource DTOs use a prefix from the parent)
        // E.g. "AgreementComponent" → last word is "Component"
        if (preg_match('/([A-Z][a-z]+)$/', $baseName, $m)) {
            $lastWord = $m[1];
            if (in_array($lastWord, $schemaNames, true)) {
                return $lastWord;
            }
        }

        return null;
    }

    /**
     * Returns true for schemas that are helper/utility types that do not map
     * to a PHP DTO: input-only bodies, list wrappers, response envelopes, etc.
     */
    private function isInputOnlySchema(string $name): bool
    {
        // Input-only schemas (write bodies)
        if (preg_match('/^(New|Update|Patch|Create|Edit|Send|Generate|Approve|Finish|Invoice|Move)[A-Z]/', $name)) {
            return true;
        }

        // List/response/envelope wrappers
        if (preg_match('/(List|Response|Result|Entry|Entries|Ids|Settings)$/', $name)) {
            return true;
        }

        // Guess / import helpers
        if (str_contains($name, 'Guess') || str_contains($name, 'Guess')) {
            return true;
        }

        // Well-known utility schemas
        $skip = [
            'Error', 'GeneralCreated', 'GeneralLink', 'GeneralModified', 'GeneralServerModified',
            'GeneralListResponse', 'GeneralCustomFieldType', 'GeneralTableConfigField',
            'GeneralTableConfigFilter', 'GeneralSelectionCategory', 'GeneralObserverCategory',
            'LoginCredentials', 'LoginResult', 'SiteConfig', 'UserSiteConfig',
            'CustomFieldValue', 'CustomFieldValues', 'CustomFieldMapping', 'CustomFieldMappings',
            'CustomFieldMappingIds', 'Link', 'Size', 'ViewConfiguration',
            'DashboardWidgetViewConfiguration', 'DashboardWidgetType',
            'WorkLog', 'WorkLogs', 'PlanningTime', 'PlanningTimes',
            'Material', 'Materials', 'Task', 'Tasks', 'TravelLog', 'TravelLogs',
            'MapView', 'BaseLogTemplate', 'DocBeeDocumentFromTemplate',
            'CustomerObjectIds', 'MoveCustomerContact', 'MoveCustomerObject',
            'SendMessageResponse', 'SendPokeMessage', 'SendReplyMessage', 'SendForwardMessage',
            'ApproveDocBeeDocument', 'FinishDocBeeDocument', 'InvoiceDocBeeDocument',
            'GeneratedMessage', 'MessageTemplateDataResponse',
        ];

        return in_array($name, $skip, true);
    }

    /**
     * Compares spec fields against DTO fields and populates a DtoReport.
     *
     * When a DTO ?array field has a typed nested DTO annotation (e.g. @var WorkLogDTO[]|null),
     * the checker recursively compares the nested DTO against the spec array item schema
     * instead of emitting a generic "Nested sub-fields" INFO note.
     *
     * @param array<string, FieldInfo>    $specFields
     * @param array<string, DtoFieldInfo> $dtoFields
     * @param array                       $spec        Full OpenAPI spec (for recursive checks)
     * @param array<string, array>        $specSchemas All top-level spec schemas
     */
    private function compareDtoVsSpec(
        array $specFields,
        array $dtoFields,
        DtoReport $report,
        array $spec = [],
        array $specSchemas = [],
    ): void {
        // Top-level spec field names only (no nested paths for primary comparison)
        $topLevelSpecFields = array_filter(
            $specFields,
            fn(FieldInfo $f) => !str_contains($f->name, '.') && !str_contains($f->name, '['),
        );

        foreach ($topLevelSpecFields as $fieldName => $specField) {
            // Flag deprecated spec fields
            if ($specField->specDeprecated) {
                $report->deprecatedFields[] = $specField;
            }

            if (!array_key_exists($fieldName, $dtoFields)) {
                // Spec has field, DTO does not
                $report->missingFields[] = $specField;
                continue;
            }

            $dtoField = $dtoFields[$fieldName];

            // Read-only mismatch
            if ($specField->specReadOnly !== $dtoField->phpReadOnly) {
                $report->readonlyMismatches[] = [
                    'field'       => $fieldName,
                    'specReadOnly'=> $specField->specReadOnly,
                    'dtoReadOnly' => $dtoField->phpReadOnly,
                ];
            }

            // Type mismatch (loose comparison — only flags obvious mismatches)
            $specPhpType = $this->specTypeToPHP($specField->specType, $specField->specRef);
            if ($specPhpType !== null && !$this->typesAreCompatible($specPhpType, $dtoField->phpType)) {
                $report->typeMismatches[] = [
                    'field'   => $fieldName,
                    'specType'=> $specField->specType . ($specField->specRef ? " (\$ref)" : ''),
                    'dtoType' => $dtoField->phpType,
                ];
            }
        }

        // Determine which array fields have typed nested DTOs, so we can recursively
        // compare them instead of falling back to the generic INFO note.
        $typedNestedFields = []; // fieldName → nestedDtoClass
        foreach ($dtoFields as $fieldName => $dtoField) {
            if ($dtoField->nestedDtoClass !== null) {
                $typedNestedFields[$fieldName] = $dtoField->nestedDtoClass;
            }
        }

        // Process nested object/array fields that have sub-structure in the spec.
        // Fields with a typed nested DTO get recursively compared; the rest are
        // reported as informational "Nested sub-fields".
        foreach ($specFields as $fieldPath => $specField) {
            if (!str_contains($fieldPath, '.') && !str_contains($fieldPath, '[')) {
                continue; // only nested paths
            }
            // The top-level field name (before first dot or bracket)
            $topLevel = preg_split('/[.\[]/', $fieldPath)[0] ?? $fieldPath;
            if (!array_key_exists($topLevel, $dtoFields)) {
                continue;
            }

            // If this top-level field has a typed nested DTO, skip the INFO note —
            // recursive comparison is handled below.
            if (isset($typedNestedFields[$topLevel])) {
                continue;
            }

            // DTO has the field but as a flat type — this is informational
            $report->nestedObjects[] = $specField;
        }

        // Recursively compare typed nested DTO arrays against their spec item schemas.
        if ($spec !== [] && $specSchemas !== []) {
            foreach ($typedNestedFields as $fieldName => $nestedDtoClass) {
                // Only process if the spec also has this field as an array
                if (!isset($topLevelSpecFields[$fieldName])) {
                    continue;
                }
                $specField = $topLevelSpecFields[$fieldName];
                if ($specField->specType !== 'array') {
                    continue;
                }

                $nestedResult = $this->compareNestedDto($nestedDtoClass, $spec, $specSchemas);
                if ($nestedResult !== null) {
                    $report->nestedDtoResults[$fieldName] = $nestedResult;
                }
            }
        }

        // Extra fields in DTO that are not in spec
        $topLevelSpecNames = array_keys($topLevelSpecFields);
        foreach ($dtoFields as $fieldName => $dtoField) {
            if (!in_array($fieldName, $topLevelSpecNames, true)) {
                $report->extraFields[] = $dtoField;
            }
        }
    }

    /**
     * Performs a recursive compatibility check on a nested DTO class against its
     * corresponding spec schema (resolved via the same findSchemaName() heuristic).
     *
     * Returns null when the nested DTO or its schema cannot be resolved.
     *
     * @param  array<string, array> $specSchemas
     * @return array{dtoClass:string, schemaName:string, report:DtoReport}|null
     */
    private function compareNestedDto(
        string $nestedDtoClass,
        array $spec,
        array $specSchemas,
    ): ?array {
        // Resolve the full class name
        $fullClass = $this->dtoNamespace . $nestedDtoClass;
        try {
            if (!class_exists($fullClass)) {
                return null;
            }
        } catch (\Throwable) {
            return null;
        }

        // Find the corresponding spec schema
        $schemaName = $this->findSchemaName($nestedDtoClass, array_keys($specSchemas));
        if ($schemaName === null) {
            return null;
        }

        // Extract spec fields for the nested schema
        $visited    = [];
        $specFields = $this->extractFields($spec, $specSchemas[$schemaName], $visited, '');

        // Extract nested DTO fields
        $dtoFields = $this->getDtoFields($fullClass);

        // Recursively compare (pass spec/schemas for further nesting if needed)
        $nestedReport = new DtoReport();
        $nestedReport->specSchemaName = $schemaName;
        $nestedReport->schemaFound    = true;
        $this->compareDtoVsSpec($specFields, $dtoFields, $nestedReport, $spec, $specSchemas);

        return [
            'dtoClass'   => $nestedDtoClass,
            'schemaName' => $schemaName,
            'report'     => $nestedReport,
        ];
    }

    /**
     * Converts an OpenAPI type string to the equivalent PHP type name.
     * Returns null when no reliable mapping exists.
     */
    private function specTypeToPHP(?string $specType, ?string $specRef): ?string
    {
        if ($specRef !== null) {
            // $ref to another schema → could be an int (ID) or an object.
            // We can't determine this statically, so skip type comparison.
            return null;
        }

        return match ($specType) {
            'integer' => 'int',
            'number'  => 'float',
            'boolean' => 'bool',
            'string'  => 'string',
            'array'   => 'array',
            'object'  => 'array',  // PHP typically stores objects as arrays
            default   => null,
        };
    }

    /**
     * Returns true when the spec PHP type and the DTO PHP type are compatible.
     * Allows "array" to cover both array and object, and ignores nullable prefix.
     */
    private function typesAreCompatible(string $specPhpType, string $dtoPhpType): bool
    {
        // Normalise: strip leading "?" (old-style nullable)
        $a = ltrim($specPhpType, '?');
        $b = ltrim($dtoPhpType, '?');

        if ($a === $b) {
            return true;
        }

        // array and object are interchangeable in PHP
        if (in_array($a, ['array', 'object'], true) && in_array($b, ['array', 'object'], true)) {
            return true;
        }

        // If DTO stores as int but spec says object/array (foreign key reference), it's fine
        if ($b === 'int' && in_array($a, ['array', 'object'], true)) {
            return true;
        }

        return false;
    }

    // -------------------------------------------------------------------------
    // Endpoint coverage
    // -------------------------------------------------------------------------

    private function checkEndpointCoverage(array $spec): EndpointCoverage
    {
        $coverage  = new EndpointCoverage();
        $resources = $this->discoverResourceClasses();

        // Build a set of normalised endpoint patterns from resources.
        // Resource endpoints may include a "v1/" prefix (e.g. "v1/agreement") — strip it
        // to match the normalised spec paths.
        // Sub-resources store endpoints like "agreement/42/component" — normalise to
        // "agreement/*/component".
        // Additionally, each resource's method bodies are scanned for explicit
        // $this->http->*() calls so that action methods (export, getCustomFields, …)
        // and standalone Resource classes are also counted as covered.
        $versionPrefixes     = ['restapi/v1/', 'api/v1/', 'v1/'];
        $implementedPatterns = [];

        $normaliseEp = static function (string $ep) use ($versionPrefixes): string {
            $ep = strtolower($ep);
            foreach ($versionPrefixes as $prefix) {
                if (str_starts_with($ep, $prefix)) {
                    $ep = substr($ep, strlen($prefix));
                    break;
                }
            }
            $pattern = preg_replace('/\{[^}]+\}/', '*', $ep);
            $pattern = preg_replace('/\/\d+\//', '/*/', (string) $pattern);
            return rtrim((string) $pattern, '/');   // strip meaningless trailing slash
        };

        foreach ($resources as $info) {
            // 1. Base endpoint pattern (e.g. "protocol", "agreement/*/component")
            if ($info['endpoint'] !== '') {
                $implementedPatterns[] = $normaliseEp($info['endpoint']);
            }

            // 2. Method-level paths discovered by scanning HTTP call sites
            $methodPaths = $this->extractMethodEndpointsFromSource(
                $info['filePath'],
                $info['endpoint']
            );
            foreach ($methodPaths as $mp) {
                $p = $normaliseEp($mp);
                if ($p !== '') {
                    $implementedPatterns[] = $p;
                }
            }
        }

        // Build a set of top-level entity names that have at least one Resource class.
        // Used to distinguish "missing entire resource" from "missing sub-resource/action".
        $implementedTopLevel = [];
        foreach ($implementedPatterns as $p) {
            $firstSlash = strpos($p, '/');
            $topLevel   = $firstSlash !== false ? substr($p, 0, $firstSlash) : $p;
            $implementedTopLevel[$topLevel] = true;
        }

        $specPaths = $spec['paths'] ?? [];

        // Normalise spec paths — strip known API version prefixes so that
        // the path "v1/customer" matches a resource with endpoint "customer".
        // Known prefixes (tried in order, most specific first):
        $pathPrefixes = ['/restApi/v1/', '/api/v1/', '/v1/', '/'];
        $seen         = [];

        foreach ($specPaths as $path => $pathItem) {
            // Strip the common prefix
            $relative = $path;
            foreach ($pathPrefixes as $prefix) {
                if (str_starts_with($path, $prefix)) {
                    $relative = substr($path, strlen($prefix));
                    break;
                }
            }
            $relative = ltrim($relative, '/');

            // Normalise path parameters: "/customer/{id}" → "customer/*"
            // Also handles the spec typo pattern "${id}" (dollar sign before the brace)
            // where the $ sits outside the braces: "foo/${id}" → "foo/*"
            $normalised = preg_replace('/\$?\{[^}]+\}/', '*', $relative) ?? $relative;
            $normalised = strtolower($normalised);

            // Deduplicate (different HTTP methods on the same path share one entry)
            if (isset($seen[$normalised])) {
                continue;
            }
            $seen[$normalised] = true;

            // Collect the HTTP methods defined for this path in the spec
            $httpVerbs = ['get', 'post', 'put', 'patch', 'delete', 'head', 'options'];
            $methods   = [];
            foreach ($httpVerbs as $verb) {
                if (isset($pathItem[$verb])) {
                    $methods[] = strtoupper($verb);
                }
            }

            // Check if any resource covers this pattern
            $covered = false;
            $stripped = preg_replace('#/\*$#', '', $normalised) ?? $normalised;
            foreach ($implementedPatterns as $p) {
                // 1. Exact or prefix match
                if ($p === $normalised || $p === rtrim($normalised, '/*')) {
                    $covered = true;
                    break;
                }
                // 2. Match ignoring trailing /{id}
                if ($p === $stripped) {
                    $covered = true;
                    break;
                }
                // 3. Wildcard-segment matching:
                //    A '*' in the implemented pattern matches any single path segment.
                //    E.g. the pattern '*/*/filter' (from MapViewFilterResource whose
                //    endpoint is 'v1/{$type}/{$id}/filter') should match the spec path
                //    'docbeedocumentmapview/*/filter'.
                if (str_contains($p, '*')) {
                    $escapedParts = array_map(static fn($s) => preg_quote($s, '#'), explode('*', $p));
                    $rx = '#^' . implode('[^/]+', $escapedParts) . '$#';
                    if (preg_match($rx, $normalised) || preg_match($rx, $stripped)) {
                        $covered = true;
                        break;
                    }
                }
            }

            if ($covered) {
                $coverage->implemented[] = $relative;
                continue;
            }

            // Not covered — categorise
            $coverage->notImplemented[] = $relative;

            $segments  = explode('/', $normalised);
            $topLevel  = $segments[0];
            $entry     = ['path' => $relative, 'methods' => $methods];

            if (!isset($implementedTopLevel[$topLevel])) {
                // No resource class at all for this entity
                $coverage->missingResources[] = $entry;
            } elseif (count($segments) >= 3) {
                // Parent resource exists; this is a nested sub-path (e.g. agreement/*/component)
                $coverage->missingSubResources[] = $entry;
            } else {
                // Parent resource exists; path looks like a special action or missing CRUD endpoint
                $coverage->missingActions[] = $entry;
            }
        }

        sort($coverage->implemented);
        sort($coverage->notImplemented);
        usort($coverage->missingResources,    fn($a, $b) => strcmp($a['path'], $b['path']));
        usort($coverage->missingSubResources, fn($a, $b) => strcmp($a['path'], $b['path']));
        usort($coverage->missingActions,      fn($a, $b) => strcmp($a['path'], $b['path']));

        return $coverage;
    }

    // -------------------------------------------------------------------------
    // Snapshot / API drift detection
    // -------------------------------------------------------------------------

    /**
     * Compares the live spec against a previously saved snapshot and returns
     * a description of every detected change.
     */
    private function compareSpecVsSnapshot(array $spec, array $snapshot): ApiChange
    {
        $change = new ApiChange();

        // --- Schema diff ---
        $liveSchemas     = array_keys($spec['components']['schemas']     ?? []);
        $snapshotSchemas = array_keys($snapshot['components']['schemas'] ?? []);

        $change->schemasAdded   = array_values(array_diff($liveSchemas,     $snapshotSchemas));
        $change->schemasRemoved = array_values(array_diff($snapshotSchemas, $liveSchemas));

        // Per-schema field diff for schemas present in both
        $commonSchemas = array_intersect($liveSchemas, $snapshotSchemas);
        foreach ($commonSchemas as $schemaName) {
            $liveSchema     = $spec['components']['schemas'][$schemaName];
            $snapshotSchema = $snapshot['components']['schemas'][$schemaName];

            $liveVisited = $snapVisited = [];
            $liveFields  = $this->extractFields($spec,     $liveSchema,     $liveVisited, '');
            $snapFields  = $this->extractFields($snapshot, $snapshotSchema, $snapVisited, '');

            $liveNames = array_keys($liveFields);
            $snapNames = array_keys($snapFields);

            $added       = array_diff($liveNames, $snapNames);
            $removed     = array_diff($snapNames, $liveNames);
            $typeChanges = [];

            foreach (array_intersect($liveNames, $snapNames) as $fname) {
                $lt = $liveFields[$fname]->specType;
                $st = $snapFields[$fname]->specType;
                if ($lt !== $st) {
                    $typeChanges[] = "{$fname}: {$st} → {$lt}";
                }
            }

            if ($added !== [] || $removed !== [] || $typeChanges !== []) {
                $change->schemasModified[$schemaName] = [
                    'added'       => array_values($added),
                    'removed'     => array_values($removed),
                    'typeChanges' => $typeChanges,
                ];
            }
        }

        // --- Path diff ---
        $livePaths     = array_keys($spec['paths']     ?? []);
        $snapshotPaths = array_keys($snapshot['paths'] ?? []);

        $change->pathsAdded   = array_values(array_diff($livePaths,     $snapshotPaths));
        $change->pathsRemoved = array_values(array_diff($snapshotPaths, $livePaths));

        // --- Deprecated operations ---
        foreach ($spec['paths'] ?? [] as $path => $pathItem) {
            foreach ($pathItem as $method => $operation) {
                if (!is_array($operation)) {
                    continue;
                }
                if ($operation['deprecated'] ?? false) {
                    $change->operationsDeprecated[] = strtoupper((string) $method) . ' ' . $path;
                }
            }
        }

        return $change;
    }
}

// ---------------------------------------------------------------------------
// Text + JSON formatters (standalone functions for simplicity)
// ---------------------------------------------------------------------------

/**
 * Formats a CompatReport as a human-readable text report.
 */
function formatTextReport(CompatReport $report): string
{
    $sep  = str_repeat('=', 72);
    $dash = str_repeat('-', 72);
    $out  = [];

    $out[] = $sep;
    $out[] = '  Docbee API Compatibility Report';
    $out[] = $sep;
    $out[] = "  Generated : {$report->generatedAt}";
    $out[] = "  Spec URL  : {$report->specUrl}";
    if ($report->specTitle)   $out[] = "  API Title : {$report->specTitle}";
    if ($report->specVersion) $out[] = "  API Ver.  : {$report->specVersion}";
    if ($report->snapshotDate) {
        $out[] = "  Snapshot  : {$report->snapshotDate}";
    } else {
        $out[] = "  Snapshot  : none (run with --save-snapshot to create one)";
    }
    $out[] = $sep;
    $out[] = '';

    // ---- API Drift ----
    if ($report->apiChange !== null) {
        $ch = $report->apiChange;
        $out[] = '[ API CHANGES SINCE LAST SNAPSHOT ]';
        $out[] = $dash;

        if ($ch->schemasAdded === [] && $ch->schemasRemoved === []
            && $ch->schemasModified === [] && $ch->pathsAdded === []
            && $ch->pathsRemoved === [] && $ch->operationsDeprecated === []) {
            $out[] = '  (no API changes detected)';
        }

        if ($ch->schemasAdded !== []) {
            $out[] = '  NEW schemas (' . count($ch->schemasAdded) . '):';
            foreach ($ch->schemasAdded as $s) $out[] = "    + {$s}";
        }
        if ($ch->schemasRemoved !== []) {
            $out[] = '  REMOVED schemas (' . count($ch->schemasRemoved) . '):';
            foreach ($ch->schemasRemoved as $s) $out[] = "    - {$s}";
        }
        if ($ch->pathsAdded !== []) {
            $out[] = '  NEW paths (' . count($ch->pathsAdded) . '):';
            foreach ($ch->pathsAdded as $p) $out[] = "    + {$p}";
        }
        if ($ch->pathsRemoved !== []) {
            $out[] = '  REMOVED paths (' . count($ch->pathsRemoved) . '):';
            foreach ($ch->pathsRemoved as $p) $out[] = "    - {$p}";
        }
        if ($ch->operationsDeprecated !== []) {
            $out[] = '  DEPRECATED operations (' . count($ch->operationsDeprecated) . '):';
            foreach ($ch->operationsDeprecated as $op) $out[] = "    ! {$op}";
        }
        if ($ch->schemasModified !== []) {
            $out[] = '  MODIFIED schemas (' . count($ch->schemasModified) . '):';
            foreach ($ch->schemasModified as $name => $diff) {
                $out[] = "    ~ {$name}";
                foreach ($diff['added']       as $f) $out[] = "        + field: {$f}";
                foreach ($diff['removed']     as $f) $out[] = "        - field: {$f}";
                foreach ($diff['typeChanges'] as $f) $out[] = "        ~ type : {$f}";
            }
        }
        $out[] = '';
    }

    // ---- DTO Issues ----
    $out[] = '[ IMPLEMENTATION vs. SPEC (DTOs) ]';
    $out[] = $dash;

    $dtoIssueCount = 0;
    foreach ($report->dtoReports as $shortName => $r) {
        $issues = count($r->missingFields) + count($r->extraFields)
            + count($r->readonlyMismatches) + count($r->typeMismatches);

        if ($issues === 0 && $r->deprecatedFields === [] && $r->nestedObjects === [] && $r->nestedDtoResults === []) {
            continue;
        }
        $dtoIssueCount++;

        $schemaLabel = $r->schemaFound ? "  → schema: {$r->specSchemaName}" : '  → schema: NOT FOUND';
        $out[] = "  {$shortName}  {$schemaLabel}";

        if ($r->missingFields !== []) {
            $out[] = "    MISSING in DTO (" . count($r->missingFields) . " fields — present in spec):";
            foreach ($r->missingFields as $f) {
                $ro  = $f->specReadOnly  ? ' [readOnly]'  : '';
                $dep = $f->specDeprecated ? ' [deprecated]' : '';
                $type = $f->specType ? " ({$f->specType})" : '';
                $out[] = "      - {$f->name}{$type}{$ro}{$dep}";
            }
        }
        if ($r->extraFields !== []) {
            $out[] = "    EXTRA in DTO (" . count($r->extraFields) . " fields — not in spec, possibly deprecated):";
            foreach ($r->extraFields as $f) {
                $out[] = "      + {$f->name} ({$f->phpType})";
            }
        }
        if ($r->readonlyMismatches !== []) {
            $out[] = "    READ-ONLY mismatches (" . count($r->readonlyMismatches) . "):";
            foreach ($r->readonlyMismatches as $m) {
                $spec = $m['specReadOnly'] ? 'readOnly' : 'writable';
                $dto  = $m['dtoReadOnly']  ? 'readOnly' : 'writable';
                $out[] = "      ~ {$m['field']}: spec={$spec}, dto={$dto}";
            }
        }
        if ($r->typeMismatches !== []) {
            $out[] = "    TYPE mismatches (" . count($r->typeMismatches) . "):";
            foreach ($r->typeMismatches as $m) {
                $out[] = "      ~ {$m['field']}: spec={$m['specType']}, dto={$m['dtoType']}";
            }
        }
        if ($r->deprecatedFields !== []) {
            $out[] = "    DEPRECATED spec fields (" . count($r->deprecatedFields) . "):";
            foreach ($r->deprecatedFields as $f) {
                $inDto = '(not in DTO)';
                $out[] = "      ! {$f->name} {$inDto}";
            }
        }
        if ($r->nestedObjects !== []) {
            $out[] = "    INFO — Nested sub-fields in spec (stored flat in DTO, " . count($r->nestedObjects) . "):";
            foreach (array_slice($r->nestedObjects, 0, 10) as $f) {
                $out[] = "      · {$f->name}";
            }
            if (count($r->nestedObjects) > 10) {
                $out[] = "      · ... and " . (count($r->nestedObjects) - 10) . " more";
            }
        }
        if ($r->nestedDtoResults !== []) {
            foreach ($r->nestedDtoResults as $fieldName => $nested) {
                $nr = $nested['report'];
                $dtoClass   = $nested['dtoClass'];
                $schemaName = $nested['schemaName'];
                $hasNestedIssues = !empty($nr->missingFields) || !empty($nr->extraFields)
                    || !empty($nr->readonlyMismatches) || !empty($nr->typeMismatches);
                $out[] = "    NESTED {$dtoClass} → {$schemaName} schema (field: {$fieldName}[]):";
                if (!$hasNestedIssues && $nr->deprecatedFields === [] && $nr->nestedObjects === [] && $nr->nestedDtoResults === []) {
                    $out[] = "      (OK — nested DTO matches spec schema)";
                }
                if ($nr->missingFields !== []) {
                    $out[] = "      MISSING in {$dtoClass} (" . count($nr->missingFields) . " fields — present in spec):";
                    foreach ($nr->missingFields as $f) {
                        $ro   = $f->specReadOnly   ? ' [readOnly]'   : '';
                        $dep  = $f->specDeprecated ? ' [deprecated]' : '';
                        $type = $f->specType ? " ({$f->specType})" : '';
                        $out[] = "        - {$f->name}{$type}{$ro}{$dep}";
                    }
                }
                if ($nr->extraFields !== []) {
                    $out[] = "      EXTRA in {$dtoClass} (" . count($nr->extraFields) . " fields — not in spec):";
                    foreach ($nr->extraFields as $f) {
                        $out[] = "        + {$f->name} ({$f->phpType})";
                    }
                }
                if ($nr->readonlyMismatches !== []) {
                    $out[] = "      READ-ONLY mismatches (" . count($nr->readonlyMismatches) . "):";
                    foreach ($nr->readonlyMismatches as $m) {
                        $spec = $m['specReadOnly'] ? 'readOnly' : 'writable';
                        $dto  = $m['dtoReadOnly']  ? 'readOnly' : 'writable';
                        $out[] = "        ~ {$m['field']}: spec={$spec}, dto={$dto}";
                    }
                }
                if ($nr->typeMismatches !== []) {
                    $out[] = "      TYPE mismatches (" . count($nr->typeMismatches) . "):";
                    foreach ($nr->typeMismatches as $m) {
                        $out[] = "        ~ {$m['field']}: spec={$m['specType']}, dto={$m['dtoType']}";
                    }
                }
                if ($nr->deprecatedFields !== []) {
                    $out[] = "      DEPRECATED spec fields (" . count($nr->deprecatedFields) . "):";
                    foreach ($nr->deprecatedFields as $f) {
                        $out[] = "        ! {$f->name} (not in DTO)";
                    }
                }
                if ($nr->nestedObjects !== []) {
                    $out[] = "      INFO — Nested sub-fields in spec (stored flat in {$dtoClass}, " . count($nr->nestedObjects) . "):";
                    foreach (array_slice($nr->nestedObjects, 0, 5) as $f) {
                        $out[] = "        · {$f->name}";
                    }
                    if (count($nr->nestedObjects) > 5) {
                        $out[] = "        · ... and " . (count($nr->nestedObjects) - 5) . " more";
                    }
                }
            }
        }
        $out[] = '';
    }

    if ($dtoIssueCount === 0) {
        $out[] = '  All DTOs are in sync with the spec.';
        $out[] = '';
    }

    // ---- DTOs without schema ----
    if ($report->dtosMissingSchema !== []) {
        $out[] = '[ DTOs WITHOUT MATCHING SPEC SCHEMA (' . count($report->dtosMissingSchema) . ') ]';
        $out[] = $dash;
        foreach ($report->dtosMissingSchema as $s) {
            $out[] = "  ? {$s}";
        }
        $out[] = '';
    }

    // ---- Schemas without DTO ----
    if ($report->schemasMissingDto !== []) {
        $out[] = '[ SPEC SCHEMAS WITHOUT A DTO (' . count($report->schemasMissingDto) . ') ]';
        $out[] = $dash;
        $out[] = '  These schemas exist in the API but have no PHP DTO class.';
        $out[] = '  They may represent response envelopes or rarely-used endpoints.';
        foreach ($report->schemasMissingDto as $s) {
            $out[] = "  ? {$s}";
        }
        $out[] = '';
    }

    // ---- Endpoint coverage ----
    $cov = $report->endpointCoverage;
    if ($cov->notImplemented !== []) {
        $total = count($cov->notImplemented);
        $out[] = '[ API ENDPOINTS WITHOUT A RESOURCE CLASS (' . $total . ') ]';
        $out[] = $dash;

        /** @param list<array{path:string, methods:list<string>}> $entries */
        $printEntries = static function (array $entries) use (&$out): void {
            foreach ($entries as $e) {
                $methodStr = $e['methods'] !== [] ? '  [' . implode(', ', $e['methods']) . ']' : '';
                $out[] = "  - {$e['path']}{$methodStr}";
            }
        };

        if ($cov->missingResources !== []) {
            $out[] = '';
            $out[] = '  -- A) MISSING TOP-LEVEL RESOURCE (' . count($cov->missingResources) . ') --';
            $out[] = '  No PHP Resource class exists for these API entities.';
            $printEntries($cov->missingResources);
        }

        if ($cov->missingSubResources !== []) {
            $out[] = '';
            $out[] = '  -- B) MISSING SUB-RESOURCE (' . count($cov->missingSubResources) . ') --';
            $out[] = '  The parent resource is implemented but these nested endpoints are not.';
            $printEntries($cov->missingSubResources);
        }

        if ($cov->missingActions !== []) {
            $out[] = '';
            $out[] = '  -- C) MISSING ACTIONS / OPERATIONS (' . count($cov->missingActions) . ') --';
            $out[] = '  The parent resource exists but these special operations are not implemented.';
            $printEntries($cov->missingActions);
        }

        $out[] = '';
    }

    // ---- Summary ----
    $out[] = $sep;
    $out[] = '  SUMMARY';
    $out[] = $sep;
    $totalDtos     = count($report->dtoReports);
    $missingDtos   = count($report->dtosMissingSchema);
    $extraSchemas  = count($report->schemasMissingDto);
    $unimplemented = count($report->endpointCoverage->notImplemented);
    $missingRes    = count($report->endpointCoverage->missingResources);
    $missingSubRes = count($report->endpointCoverage->missingSubResources);
    $missingAct    = count($report->endpointCoverage->missingActions);

    $totalMissing   = array_sum(array_map(fn($r) => count($r->missingFields),       $report->dtoReports));
    $totalExtra     = array_sum(array_map(fn($r) => count($r->extraFields),         $report->dtoReports));
    $totalReadonly  = array_sum(array_map(fn($r) => count($r->readonlyMismatches),  $report->dtoReports));
    $totalType      = array_sum(array_map(fn($r) => count($r->typeMismatches),      $report->dtoReports));
    $totalDeprecated = array_sum(array_map(fn($r) => count($r->deprecatedFields),   $report->dtoReports));

    // Include nested DTO issue counts in summary totals
    foreach ($report->dtoReports as $r) {
        foreach ($r->nestedDtoResults as $nested) {
            $nr = $nested['report'];
            $totalMissing  += count($nr->missingFields);
            $totalExtra    += count($nr->extraFields);
            $totalReadonly += count($nr->readonlyMismatches);
            $totalType     += count($nr->typeMismatches);
            $totalDeprecated += count($nr->deprecatedFields);
        }
    }

    $out[] = sprintf('  %-44s %d', 'DTOs checked:',                      $totalDtos);
    $out[] = sprintf('  %-44s %d', 'DTOs without spec schema:',           $missingDtos);
    $out[] = sprintf('  %-44s %d', 'Spec schemas without DTO:',           $extraSchemas);
    $out[] = sprintf('  %-44s %d', 'Fields missing in DTOs:',             $totalMissing);
    $out[] = sprintf('  %-44s %d', 'Extra fields in DTOs:',               $totalExtra);
    $out[] = sprintf('  %-44s %d', 'Read-only mismatches:',               $totalReadonly);
    $out[] = sprintf('  %-44s %d', 'Type mismatches:',                    $totalType);
    $out[] = sprintf('  %-44s %d', 'Deprecated spec fields:',             $totalDeprecated);
    $out[] = sprintf('  %-44s %d', 'Unimplemented endpoints (total):',    $unimplemented);
    $out[] = sprintf('  %-44s %d', '  · Missing resources:',              $missingRes);
    $out[] = sprintf('  %-44s %d', '  · Missing sub-resources:',          $missingSubRes);
    $out[] = sprintf('  %-44s %d', '  · Missing actions/operations:',     $missingAct);

    $hasIssues = ($totalMissing + $totalExtra + $totalReadonly + $totalType
        + $missingDtos + $unimplemented) > 0;

    $out[] = '';
    $out[] = $hasIssues
        ? '  STATUS: Issues found — see sections above for details.'
        : '  STATUS: OK — implementation matches the spec.';
    $out[] = $sep;

    return implode(PHP_EOL, $out) . PHP_EOL;
}

/**
 * Serialises a CompatReport to a JSON structure suitable for machine processing.
 */
function formatJsonReport(CompatReport $report): string
{
    $data = [
        'meta' => [
            'generatedAt'  => $report->generatedAt,
            'specUrl'      => $report->specUrl,
            'specTitle'    => $report->specTitle,
            'specVersion'  => $report->specVersion,
            'snapshotDate' => $report->snapshotDate,
        ],
        'summary' => [
            'hasIssues'           => $report->hasIssues(),
            'dtosChecked'         => count($report->dtoReports),
            'dtosMissingSchema'   => count($report->dtosMissingSchema),
            'schemasMissingDto'   => count($report->schemasMissingDto),
            'totalMissingFields'  => array_sum(array_map(fn($r) => count($r->missingFields),      $report->dtoReports)),
            'totalExtraFields'    => array_sum(array_map(fn($r) => count($r->extraFields),        $report->dtoReports)),
            'totalReadonlyMismatches' => array_sum(array_map(fn($r) => count($r->readonlyMismatches), $report->dtoReports)),
            'totalTypeMismatches' => array_sum(array_map(fn($r) => count($r->typeMismatches),     $report->dtoReports)),
            'totalDeprecated'     => array_sum(array_map(fn($r) => count($r->deprecatedFields),   $report->dtoReports)),
            'unimplementedEndpoints'       => count($report->endpointCoverage->notImplemented),
            'missingResources'             => count($report->endpointCoverage->missingResources),
            'missingSubResources'          => count($report->endpointCoverage->missingSubResources),
            'missingActions'               => count($report->endpointCoverage->missingActions),
        ],
        'apiChanges' => null,
        'dtoIssues'  => [],
        'dtosMissingSchema'   => $report->dtosMissingSchema,
        'schemasMissingDto'   => $report->schemasMissingDto,
        'endpointCoverage'    => [
            'implemented'       => $report->endpointCoverage->implemented,
            'notImplemented'    => $report->endpointCoverage->notImplemented,
            'missingResources'  => $report->endpointCoverage->missingResources,
            'missingSubResources' => $report->endpointCoverage->missingSubResources,
            'missingActions'    => $report->endpointCoverage->missingActions,
        ],
    ];

    if ($report->apiChange !== null) {
        $ch = $report->apiChange;
        $data['apiChanges'] = [
            'schemasAdded'          => $ch->schemasAdded,
            'schemasRemoved'        => $ch->schemasRemoved,
            'schemasModified'       => $ch->schemasModified,
            'pathsAdded'            => $ch->pathsAdded,
            'pathsRemoved'          => $ch->pathsRemoved,
            'operationsDeprecated'  => $ch->operationsDeprecated,
        ];
    }

    foreach ($report->dtoReports as $shortName => $r) {
        $data['dtoIssues'][$shortName] = [
            'schemaName'          => $r->specSchemaName,
            'schemaFound'         => $r->schemaFound,
            'missingFields'       => array_map(fn(FieldInfo $f) => [
                'name'       => $f->name,
                'type'       => $f->specType,
                'readOnly'   => $f->specReadOnly,
                'deprecated' => $f->specDeprecated,
            ], $r->missingFields),
            'extraFields'         => array_map(fn(DtoFieldInfo $f) => [
                'name' => $f->name,
                'type' => $f->phpType,
            ], $r->extraFields),
            'readonlyMismatches'  => $r->readonlyMismatches,
            'typeMismatches'      => $r->typeMismatches,
            'deprecatedFields'    => array_map(fn(FieldInfo $f) => $f->name, $r->deprecatedFields),
            'nestedObjectFields'  => array_map(fn(FieldInfo $f) => $f->name, $r->nestedObjects),
            'nestedDtoResults'    => array_map(fn(array $n) => [
                'dtoClass'   => $n['dtoClass'],
                'schemaName' => $n['schemaName'],
                'missingFields' => array_map(fn(FieldInfo $f) => [
                    'name'       => $f->name,
                    'type'       => $f->specType,
                    'readOnly'   => $f->specReadOnly,
                    'deprecated' => $f->specDeprecated,
                ], $n['report']->missingFields),
                'extraFields' => array_map(fn(DtoFieldInfo $f) => [
                    'name' => $f->name,
                    'type' => $f->phpType,
                ], $n['report']->extraFields),
                'readonlyMismatches' => $n['report']->readonlyMismatches,
                'typeMismatches'     => $n['report']->typeMismatches,
            ], $r->nestedDtoResults),
        ];
    }

    try {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) {
        return json_encode(['error' => 'JSON encode failed: ' . $e->getMessage()]) ?: '{}';
    }
}
