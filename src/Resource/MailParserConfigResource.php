<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\MailParserConfigDTO;

/**
 * Provides access to Docbee MailParserConfig records.
 *
 * @extends AbstractResource<MailParserConfigDTO>
 */
final class MailParserConfigResource extends AbstractResource
{
    protected string $endpoint = 'v1/mailParserConfig';
    protected string $dtoClass = MailParserConfigDTO::class;
    protected string $listKey  = 'mailParserConfig';
}
