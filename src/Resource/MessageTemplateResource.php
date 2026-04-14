<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\MessageTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee MessageTemplate records.
 *
 * @extends AbstractResource<MessageTemplateDTO>
 */
final class MessageTemplateResource extends AbstractResource
{
    protected string $endpoint = 'v1/messageTemplate';
    protected string $dtoClass = MessageTemplateDTO::class;
    protected string $listKey  = 'messageTemplate';

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
    public function getDefault(string $type, string $format): array { return $this->http->get("{$this->endpoint}/default/{$type}/{$format}"); }
}