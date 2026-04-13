<?php

/**
 * Patches DTO files to use typed DTO arrays instead of raw ?array.
 *
 * For each patch: replaces the @var annotation comment + fromArray() mapping.
 */
declare(strict_types=1);

$root = dirname(__DIR__);

// [dtoFile, fieldName, typedDtoClass, specDescription]
$patches = [
    // DashboardDTO.widgets
    ['src/DTO/DashboardDTO.php', 'widgets', 'DashboardWidgetDTO', 'list of dashboard widgets'],
    // DocBeeScriptDTO.params
    ['src/DTO/DocBeeScriptDTO.php', 'params', 'DocBeeScriptParameterDTO', 'params data'],
    // ProtocolDTO.protocolEntries + groups
    ['src/DTO/ProtocolDTO.php', 'protocolEntries', 'ProtocolEntryDTO', 'list of protocol entries'],
    ['src/DTO/ProtocolDTO.php', 'groups', 'ProtocolGroupDataDTO', 'list of protocol groups'],
    // TicketBoardDTO.columns + fields + filters
    ['src/DTO/TicketBoardDTO.php', 'columns', 'TicketBoardColumnDTO', 'list of board columns'],
    ['src/DTO/TicketBoardDTO.php', 'fields', 'TableConfigStorageFieldDTO', 'list of table config fields'],
    ['src/DTO/TicketBoardDTO.php', 'filters', 'TableConfigStorageFilterDTO', 'list of table config filters'],
    // TicketMailParserConfigDTO.newTags
    ['src/DTO/TicketMailParserConfigDTO.php', 'newTags', 'TagDTO', 'list of tags'],
    // customFields → CustomFieldValueDTO
    ['src/DTO/CustomerDTO.php', 'customFields', 'CustomFieldValueDTO', 'list of customFieldValues'],
    ['src/DTO/CustomerContactDTO.php', 'customFields', 'CustomFieldValueDTO', 'list of customFieldValues'],
    ['src/DTO/CustomerLocationDTO.php', 'customFields', 'CustomFieldValueDTO', 'list of customFieldValues'],
    ['src/DTO/CustomerObjectDTO.php', 'customFields', 'CustomFieldValueDTO', 'list of customFieldValues'],
    ['src/DTO/SelectionValueDTO.php', 'customFields', 'CustomFieldValueDTO', 'list of customFieldValues'],
    ['src/DTO/ServiceProviderDTO.php', 'customFields', 'CustomFieldValueDTO', 'list of customFieldValues'],
    ['src/DTO/ServiceProviderUserDTO.php', 'customFields', 'CustomFieldValueDTO', 'list of customFieldValues'],
    ['src/DTO/TicketDTO.php', 'customFields', 'CustomFieldValueDTO', 'list of customFieldValues'],
    ['src/DTO/DocBeeDocumentDTO.php', 'customFields', 'CustomFieldValueDTO', 'list of customFieldValues'],
    // customFields → CustomFieldMappingDTO
    ['src/DTO/ObjectCategoryDTO.php', 'customFields', 'CustomFieldMappingDTO', 'list of customField mappings'],
    ['src/DTO/ObserverCategoryDTO.php', 'customFields', 'CustomFieldMappingDTO', 'list of customField mappings'],
    ['src/DTO/SelectionCategoryDTO.php', 'customFields', 'CustomFieldMappingDTO', 'list of customField mappings'],
    // travelLog → TravelLogDTO
    ['src/DTO/DocBeeDocumentDTO.php', 'travelLog', 'TravelLogDTO', 'list of travel logs'],
    // slaReports → TicketSlaReportDTO
    ['src/DTO/TicketDTO.php', 'slaReports', 'TicketSlaReportDTO', 'sla report datas'],
];

$changed = 0;
$failed  = [];

foreach ($patches as [$relFile, $field, $dtoClass, $desc]) {
    $file = $root . '/' . $relFile;
    if (!file_exists($file)) {
        $failed[] = "$relFile: file not found";
        continue;
    }

    $src = file_get_contents($file);
    $modified = false;

    // 1. Add/update @var comment on the constructor param
    //    Pattern: existing comment (/** ... */) OR no comment, followed by
    //    "private ?array $field"
    //    We want: /** @var DtoClass[]|null desc */\n<indent>private ?array $field

    // First check if already patched with the correct type
    if (str_contains($src, "@var {$dtoClass}[]|null")) {
        echo "SKIP (already patched): {$relFile}::{$field}\n";
        continue;
    }

    // Match the constructor parameter with optional preceding comment
    // Pattern: capture indentation + optional existing comment + "private (?readonly )?(?static )?mixed|?array $field"
    $paramPattern = '/^(\s+)(?:\/\*\*[^\n]*\*\/\n\s+)?(?:\/\*[^\n]*\*\/\n\s+)?(private(?:\s+readonly)?\s+\?array\s+\$' . preg_quote($field, '/') . ')\b/m';

    if (preg_match($paramPattern, $src, $m, PREG_OFFSET_CAPTURE)) {
        $indent    = $m[1][0];
        $fullMatch = $m[0][0];
        $offset    = $m[0][1];
        $param     = $m[2][0]; // e.g. "private ?array $field"

        $replacement = $indent . '/** @var ' . $dtoClass . '[]|null ' . $desc . ' */' . "\n"
                     . $indent . $param;

        $src = substr_replace($src, $replacement, $offset, strlen($fullMatch));
        $modified = true;
    } else {
        $failed[] = "$relFile::{$field} — param not found";
        continue;
    }

    // 2. Update fromArray() mapping
    //    Old: isset($data['field']) && is_array($data['field']) ? $data['field'] : null,
    //    New: isset($data['field']) && is_array($data['field']) ? array_map(fn($x) => DtoClass::fromArray($x), $data['field']) : null,
    $oldMapping = "isset(\$data['{$field}']) && is_array(\$data['{$field}']) ? \$data['{$field}'] : null";
    $newMapping = "isset(\$data['{$field}']) && is_array(\$data['{$field}'])\n"
                . "                ? array_map(fn(\$x) => {$dtoClass}::fromArray(\$x), \$data['{$field}'])\n"
                . "                : null";

    if (str_contains($src, $oldMapping)) {
        $src = str_replace($oldMapping, $newMapping, $src);
        $modified = true;
    } else {
        // Try alternate formatting
        $alt = "isset(\$data['{$field}']) && is_array(\$data['{$field}']) ? \$data['{$field}'] : null,";
        if (str_contains($src, $alt)) {
            $src = str_replace($alt, $newMapping . ',', $src);
            $modified = true;
        } else {
            $failed[] = "$relFile::{$field} — fromArray mapping not found (comment patched but mapping unchanged)";
        }
    }

    if ($modified) {
        file_put_contents($file, $src);
        echo "Updated: {$relFile}::{$field} → {$dtoClass}[]\n";
        $changed++;
    }
}

echo "\nDone. Changed: {$changed}, Failed: " . count($failed) . "\n";
foreach ($failed as $f) echo "  !! {$f}\n";
