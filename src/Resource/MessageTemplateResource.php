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
    protected string $endpoint = 'messageTemplate';
    protected string $dtoClass = MessageTemplateDTO::class;
    protected string $listKey  = 'messageTemplate';

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }

    /**
     * @return array<string, mixed>
     * @throws \InvalidArgumentException when $type or $format is empty.
     */
    public function getDefault(string $type, string $format): array
    {
        if (trim($type) === '' || trim($format) === '') {
            throw new \InvalidArgumentException('getDefault(): type and format must not be empty.');
        }
        return $this->http->get("{$this->endpoint}/default/" . rawurlencode($type) . '/' . rawurlencode($format));
    }
}