<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ExportProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ExportProfile records.
 *
 * @extends AbstractResource<ExportProfileDTO>
 */
final class ExportProfileResource extends AbstractResource
{
    protected string $endpoint = 'exportProfile';
    protected string $dtoClass = ExportProfileDTO::class;
    protected string $listKey  = 'exportProfile';

    /** @return list<array<string, mixed>> */
    public function getExportEncodings(): array { return $this->http->get("{$this->endpoint}/exportEncoding")['exportEncoding'] ?? []; }
    /** @return list<array<string, mixed>> */
    public function getExportFormats(): array   { return $this->http->get("{$this->endpoint}/exportFormat")['exportFormat'] ?? []; }
    /** @return list<array<string, mixed>> */
    public function getExportTypes(): array     { return $this->http->get("{$this->endpoint}/exportType")['exportType'] ?? []; }
    /** @return list<array<string, mixed>> */
    public function getFieldDomains(): array    { return $this->http->get("{$this->endpoint}/fieldDomain")['fieldDomain'] ?? []; }
    /** @return list<array<string, mixed>> */
    public function getFieldDomainProperties(): array                { return $this->http->get("{$this->endpoint}/fieldDomainProperty")['fieldDomainProperty'] ?? []; }
    /** @return list<array<string, mixed>> */
    public function getFieldDomainPropertyDataFormatters(): array    { return $this->http->get("{$this->endpoint}/fieldDomainPropertyDataFormatter")['fieldDomainPropertyDataFormatter'] ?? []; }

    // By-ID lookup methods (spec uses ${id} notation — treated as standard {id})
    /** @return array<string, mixed> */
    public function getExportEncoding(int $id): array                        { return $this->http->get("{$this->endpoint}/exportEncoding/{$id}"); }
    /** @return array<string, mixed> */
    public function getExportFormat(int $id): array                          { return $this->http->get("{$this->endpoint}/exportFormat/{$id}"); }
    /** @return array<string, mixed> */
    public function getExportType(int $id): array                            { return $this->http->get("{$this->endpoint}/exportType/{$id}"); }
    /** @return array<string, mixed> */
    public function getFieldDomain(int $id): array                           { return $this->http->get("{$this->endpoint}/fieldDomain/{$id}"); }
    /** @return array<string, mixed> */
    public function getFieldDomainProperty(int $id): array                   { return $this->http->get("{$this->endpoint}/fieldDomainProperty/{$id}"); }
    /** @return array<string, mixed> */
    public function getFieldDomainPropertyDataFormatter(int $id): array      { return $this->http->get("{$this->endpoint}/fieldDomainPropertyDataFormatter/{$id}"); }
}