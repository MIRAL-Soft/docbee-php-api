<?php

namespace miralsoft\docbee\api;

class APICall
{
    /** @var object The curl object for this connection */
    protected static $curl = null;

    /**
     * Do a API Call
     *
     * @param string $function The function to call
     * @param string $token The token for the call
     * @param array $data The data for this call
     * @param bool $post Is this call a post call
     * @return string The result of the call
     */
    public static function call(Config $config, string $function, array $data = array(), bool $post = false): string
    {
        self::prepareCall($config, $function, $data, $post);

        // Get the result of curl
        $result = curl_exec(self::$curl);

        // Close the call
        self::closeCall();

        return $result;
    }

    /**
     * @param Config $config The config object with all values to login
     * @return bool|string
     */
    public static function login(Config $config)
    {
        // If no token generating is necessary
        if ($config->isOnlyUseToken()) return true;

        $url = $config->getLoginUri();
        self::$curl = curl_init($url);
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt(self::$curl, CURLOPT_POSTFIELDS, json_encode([
            'username' => $config->getUser(),
            'passwort' => $config->getPassword(),
            'lifeTime' => $config->getLifetime(),
            'lifeTimeRefresh' => $config->isLifetimeRefresh()
        ]));

        curl_setopt(self::$curl, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Content-Type: application/json')
        );

        // Get the result of curl
        $result = curl_exec(self::$curl);

        // Close the call
        self::closeCall();

        // Function is not ready to use, but it should be ok - if needed we have to edit it

        return $result;
    }

    /**
     * Prepare the API Call
     */
    protected static function prepareCall(Config $config, $function, array $data = array(), $post = false)
    {
        $url = $config->getUri() . $function . (!$post && count($data) > 0 ? ('?' . http_build_query($data)) : '');
        self::$curl = curl_init($url);
        curl_setopt(self::$curl, CURLOPT_CUSTOMREQUEST, ($post ? "POST" : 'GET'));
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);

        // Set Data to curl call
        if ($post) curl_setopt(self::$curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(self::$curl, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer " . $config->getToken(),
                'Content-Type: application/json')
        );
    }

    /**
     * Close the connection
     */
    protected static function closeCall()
    {
        curl_close(self::$curl);
    }
}