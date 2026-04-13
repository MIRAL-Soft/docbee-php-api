<?php

declare(strict_types=1);

/**
 * Configuration for the Docbee API compatibility checker.
 *
 * This file is intentionally committed to the repository because the Docbee
 * OpenAPI specification is publicly accessible and the URL is part of the
 * official API documentation.
 *
 * Usage:
 *   # Run a full compatibility report (stdout)
 *   php bin/check-api-compat.php
 *
 *   # Save a JSON report to a file
 *   php bin/check-api-compat.php --json-output=var/api-compat/report.json
 *
 *   # Update the local snapshot after reviewing API changes
 *   php bin/check-api-compat.php --save-snapshot
 *
 *   # Combined: update snapshot and write both report formats
 *   php bin/check-api-compat.php --save-snapshot --output=var/api-compat/report.txt --json-output=var/api-compat/report.json
 */
return [

    // -------------------------------------------------------------------------
    // Live API specification
    // -------------------------------------------------------------------------

    /**
     * URL to the live Docbee OpenAPI specification (JSON).
     * Update this value if Docbee moves the spec to a different URL.
     */
    'spec_url' => 'https://pcs.docbee.com/restApi/v1/openapi.json',

    /**
     * HTTP timeout in seconds when fetching the remote spec.
     */
    'fetch_timeout' => 15,

    // -------------------------------------------------------------------------
    // Snapshot (API drift detection)
    // -------------------------------------------------------------------------

    /**
     * Path to the local snapshot file.
     *
     * The snapshot stores the last acknowledged state of the API spec and is
     * used to detect drift: fields added, removed, or changed since the last
     * --save-snapshot run.  Commit this file to track API history over time.
     */
    'snapshot_file' => __DIR__ . '/api-spec-snapshot.json',

    // -------------------------------------------------------------------------
    // Report output
    // -------------------------------------------------------------------------

    /**
     * Default directory for saved reports.
     * Created automatically if it does not exist when --output or --json-output
     * flags are used.
     */
    'report_dir' => __DIR__ . '/../var/api-compat/',

    // -------------------------------------------------------------------------
    // PHP implementation paths
    // -------------------------------------------------------------------------

    /**
     * Fully-qualified namespace prefix for DTO classes (trailing backslash).
     */
    'dto_namespace' => 'miralsoft\\docbee\\api\\DTO\\',

    /**
     * Absolute path to the directory containing DTO source files.
     */
    'dto_path' => __DIR__ . '/../src/DTO/',

    /**
     * Fully-qualified namespace prefix for Resource classes (trailing backslash).
     */
    'resource_namespace' => 'miralsoft\\docbee\\api\\Resource\\',

    /**
     * Absolute path to the directory containing Resource source files.
     */
    'resource_path' => __DIR__ . '/../src/Resource/',

    // -------------------------------------------------------------------------
    // DTO classes that intentionally have no matching spec schema
    // -------------------------------------------------------------------------

    /**
     * Short class names (without namespace) that the checker should skip.
     * These are internal helper classes or DTOs with custom handling.
     */
    'dto_skip' => [
        'AbstractDTO',
        'WebhookDTO',
        'WebhookLinkDTO',
        'RequestTypeDTO',
    ],

    // -------------------------------------------------------------------------
    // Manual DTO → spec schema name overrides
    // -------------------------------------------------------------------------

    /**
     * Maps a DTO short name to the exact spec schema name when the auto-
     * detection heuristic cannot find the right match.
     *
     * Common cases: sub-resource DTOs whose spec schema uses a shorter name.
     *   e.g. "AgreementComponentDTO" is the PHP name but the spec uses "Component".
     *
     * Update this map whenever a DTO name diverges from the spec schema name.
     */
    'dto_schema_map' => [
        'AgreementComponentDTO'         => 'Component',
        'AgreementPeriodDTO'            => 'Period',
        'ContingentItemDTO'             => 'Item',
        'ContingentItemRecurrenceDTO'   => 'ItemRecurrence',
        'SlaProfileSpecializationDTO'   => 'SlaSpecialization',
        'SlaProfileWorkingHourDTO'      => 'WorkingHour',
        'DocBeeDocumentDTO'             => 'DocBeeDocument',
        'DocBeeDocumentTemplateDTO'     => 'DocBeeDocumentTemplate',
        'DocBeeDocumentTaskDTO'         => 'Task',
        'WorkLogDTO'                    => 'WorkLog',
        'PlanningTimeDTO'               => 'PlanningTime',
        'MaterialDTO'                   => 'Material',
        'CustomFieldValueDTO'           => 'CustomFieldValue',
        'CustomFieldMappingDTO'         => 'CustomFieldMapping',
        'TravelLogDTO'                  => 'TravelLog',
        'TicketSlaReportDTO'            => 'TicketSlaReport',
        'TableConfigStorageFieldDTO'    => 'TableConfigStorageField',
        'TableConfigStorageFilterDTO'   => 'TableConfigStorageFilter',
    ],
];
