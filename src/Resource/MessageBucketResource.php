<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\MessageBucketDTO;

/**
 * Provides access to Docbee MessageBucket records.
 *
 * @extends AbstractResource<MessageBucketDTO>
 */
final class MessageBucketResource extends AbstractResource
{
    protected string $endpoint = 'messageBucket';
    protected string $dtoClass = MessageBucketDTO::class;
    protected string $listKey  = 'messageBucket';
}
