<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\AgreementDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

use miralsoft\docbee\api\Resource\Concerns\NotSearchable;
/**
 * Provides access to Docbee Agreement records.
 *
 * @extends AbstractResource<AgreementDTO>
 */
final class AgreementResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'agreement';
    protected string $dtoClass = AgreementDTO::class;
    protected string $listKey  = 'agreement';

    public function export(int $exportProfileId): string { return $this->http->getRaw("{$this->endpoint}/export/{$exportProfileId}"); }
    /** @param int[] $ids */
    public function exportByIds(int $exportProfileId, array $ids): string { return $this->postExportByIds("{$this->endpoint}/exportByIds/{$exportProfileId}", $ids); }
}