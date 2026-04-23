<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/** Provides access to Docbee AI features. */
final class AiResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /** Generate a mail message using AI. */
    public function generateMailMessage(array $data): array
    {
        return $this->http->post('ai/generateMailMessage', $data);
    }

    /** Generate a task description using AI. */
    public function generateTaskDescription(array $data): array
    {
        return $this->http->post('ai/generateTaskDescription', $data);
    }
}
