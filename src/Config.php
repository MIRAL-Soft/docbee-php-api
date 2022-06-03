<?php

namespace miralsoft\docbee\api;

/**
 * Configuration file for calling
 */
class Config
{
    /** @var string The URI to the API */
    protected string $uri = 'https://pcs.docbee.com/restApi/v1/';

    /** @var string The string to call the login API */
    protected string $loginUri = 'login';

    /** @var string The bearer token for the communication with docbee */
    protected string $token = '';

    /** @var string The user for login in docbee */
    protected string $user = '';

    /** @var string The password for login in docbee */
    protected string $password = '';

    /** @var int The lifetime in minutes for a connection */
    protected int $lifetime = 15;

    /** @var bool true = Lifetime have to refreshed */
    protected bool $lifetimeRefresh = true;

    /** @var bool Should only use Token without login */
    protected bool $onlyUseToken = false;

    /**
     * Constructor to set quickly the token, if exists
     *
     * @param string $token The token for the connection
     */
    public function __construct(string $token = '')
    {
        // If token is set in constructor login is not necessary
        if($token != '')    $this->setOnlyUseToken(true);

        $this->setToken($token);
    }

    /**
     * The mainuri from docbee
     * @return string The mainuri
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Set's the main uri from docbee
     *
     * @param string $uri the mainuri
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * Get's the login uri
     * @return string The login uri
     */
    public function getLoginUri(): string
    {
        return $this->getUri() . $this->loginUri;
    }

    /**
     * Checks if the config has set a token
     *
     * @return bool true = token is here
     */
    public function hasToken(): bool
    {
        return $this->token != '';
    }

    /**
     * Sets the bearer token in config
     *
     * @param string $token
     * @return void
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string The bearer token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Checks if a user is set
     *
     * @return bool true = user is set
     */
    public function hasUser(): bool
    {
        return $this->user != '';
    }

    /**
     * @return string The username
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user The username
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string The password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password The password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * @param int $lifetime
     */
    public function setLifetime(int $lifetime): void
    {
        $this->lifetime = $lifetime;
    }

    /**
     * @return bool
     */
    public function isLifetimeRefresh(): bool
    {
        return $this->lifetimeRefresh;
    }

    /**
     * @param bool $lifetimeRefresh
     */
    public function setLifetimeRefresh(bool $lifetimeRefresh): void
    {
        $this->lifetimeRefresh = $lifetimeRefresh;
    }

    /**
     * @return bool
     */
    public function isOnlyUseToken(): bool
    {
        return $this->onlyUseToken;
    }

    /**
     * @param bool $onlyUseToken
     */
    protected function setOnlyUseToken(bool $onlyUseToken): void
    {
        $this->onlyUseToken = $onlyUseToken;
    }

}