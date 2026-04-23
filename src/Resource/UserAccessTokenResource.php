<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\AccessTokenDTO;

/**
 * Provides access to Docbee AccessToken records (sub-resource of user/me).
 *
 * @extends AbstractResource<AccessTokenDTO>
 */
final class UserAccessTokenResource extends AbstractResource
{
    protected string $dtoClass = AccessTokenDTO::class;
    protected string $listKey  = 'accessToken';

    public function __construct(HttpClientInterface $http)
    {
        $this->endpoint = 'user/me/accessToken';
        parent::__construct($http);
    }

    public function revoke(int $id): void
    {
        $this->http->put("{$this->endpoint}/{$id}/revoke", []);
    }
}
