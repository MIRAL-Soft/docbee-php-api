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
    protected string $endpoint = 'v1/exportProfile';
    protected string $dtoClass = ExportProfileDTO::class;
    protected string $listKey  = 'exportProfile';

    public function getExportEncodings(): array { return $this->http->get("{$this->endpoint}/exportEncoding")['exportEncoding'] ?? []; }
    public function getExportFormats(): array { return $this->http->get("{$this->endpoint}/exportFormat")['exportFormat'] ?? []; }
    public function getExportTypes(): array { return $this->http->get("{$this->endpoint}/exportType")['exportType'] ?? []; }
    public function getFieldDomains(): array { return $this->http->get("{$this->endpoint}/fieldDomain")['fieldDomain'] ?? []; }
    public function getFieldDomainProperties(): array { return $this->http->get("{$this->endpoint}/fieldDomainProperty")['fieldDomainProperty'] ?? []; }
    public function getFieldDomainPropertyDataFormatters(): array { return $this->http->get("{$this->endpoint}/fieldDomainPropertyDataFormatter")['fieldDomainPropertyDataFormatter'] ?? []; }
}