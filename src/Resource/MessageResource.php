<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/**
 * Provides access to Docbee messaging operations.
 */
final class MessageResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /**
     * Send a mail message.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function sendMail(array $data): array
    {
        return $this->http->post('message/sendMail', $data);
    }
}
