<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DocBeeScriptDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee DocBeeScript records.
 *
 * @extends AbstractResource<DocBeeScriptDTO>
 */
final class DocBeeScriptResource extends AbstractResource
{
    protected string $endpoint = 'docBeeScript';
    protected string $dtoClass = DocBeeScriptDTO::class;
    protected string $listKey  = 'docBeeScript';

    public function clearLog(int $scriptId): void { $this->http->post("{$this->endpoint}/{$scriptId}/clearLog"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function execute(int $scriptId, array $data = []): array { return $this->http->post("{$this->endpoint}/{$scriptId}/execute", $data); }
}