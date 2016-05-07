<?php
/**
 * Mojang/Minecraft Server and Services Status.
 *
 * Uses Mojangs APIs to get specific information about Minecraft servers, users and services.
 * Read more at http://wiki.vg/
 *
 * @author      Ben Christopher Tomlin <ben@tomlin.no>
 * @copyright   Copyright (c) 2016 [bct]productions (http://tomlin.no)
 * @license     The MIT License (MIT)
 * @version     1.0 (May 2016)
 */

namespace bct;

class MCStatus
{
    const AUTH_SERVER    = 'https://authserver.mojang.com/';
    const SESSION_SERVER = 'https://sessionserver.mojang.com/';
    const STATUS_SERVER  = 'https://status.mojang.com/';
    const API_SERVER     = 'https://api.mojang.com/';
    const REALM_SERVER   = 'https://mcoapi.minecraft.net/';

    const JSON = 'Content-Type: application/json\r\n';
    const STORE = 'auth-tokens.ini';

    private $name = null;
    private $uuid = null;
    private $account_id = null;
    private $client_token = null;
    private $access_token = null;

    /**
     * MCStatus constructor. Optional parameters are needed for calls that require authentication, if not stored in file.
     * @param string $name         Username of the Mojang/Minecraft account to use
     * @param string $account_id   Mojang account id (usually email address) to authenticate
     * @param string $access_token Optional access token for authentication
     * @param string $client_token Optional client token for authentication
     */
    public function __construct($name, $account_id, $access_token = null, $client_token = null) {
        $this->name = $name;
        $this->account_id = $account_id;
        $this->access_token = $access_token;
        $this->client_token = $client_token;
    }

    /**
     * Get current status of Mojang services. Does not require authentication.
     * @return array|bool Response data array or false on error
     */
    public function status() {
        return $this->http(self::STATUS_SERVER, 'check');
    }

    /**
     * Get the UUID for the specified username. Does not require authentication.
     * @param string $username Mojang account username
     * @return array|bool Response data array or false on error
     */
    public function uuid($username) {
        return $this->http(self::API_SERVER, "users/profiles/minecraft/$username");
    }

    /**
     * Get a list of UUIDs for several usernames. Does not require authentication.
     * @param array $usernames An list containing Mojang account usernames
     * @return array|bool      Response data array or false on error
     */
    public function uuids($usernames) {
        return $this->http(self::API_SERVER, 'profiles/minecraft', 'POST', self::JSON, $usernames);
    }

    /**
     * Get basic profile information about a user specified by its UUID. Does not require authentication.
     * @param string $uuid UUID of a Mojang user
     * @return array|bool  Response data array or false on error
     */
    public function profile($uuid) {
        return $this->http(self::SESSION_SERVER, "session/minecraft/profile/$uuid");
    }

    /**
     * Get more detailed user account info of current authenticated user. Requires authentication!
     * @return array|bool Response data array or false on error
     */
    public function userinfo() {
        return $this->http(self::API_SERVER, 'user', 'GET', 'Authorization: Bearer ' . $this->access_token . PHP_EOL);
    }

    /**
     * Get a list of realms (with info) that the current user has access to. Requires authentication!
     * @return array|bool Response data array or false on error
     */
    public function realms() {
        if ($this->uuid === null) {
            $this->uuid = $this->uuid($this->name)['id'];
        }
        return $this->http(self::REALM_SERVER, 'worlds', 'GET', $this->headers());
    }

    /**
     * Get info about a realm specified by its id. Requires authentication and ownership to specified realm!
     * @param integer $id Identification number of the realm
     * @return array|bool Response data array or false on error
     */
    public function realm($id) {
        if ($this->uuid === null) {
            $this->uuid = $this->uuid($this->name)['id'];
        }
        return $this->http(self::REALM_SERVER, "worlds/$id", 'GET', $this->headers());
    }

    /**
     * Authenticates a user and gets a valid access token.
     * @param string $password Mojang account password
     * @return array|bool      Response data on success, false on error
     */
    public function authenticate($password) {
        $result = $this->http_auth('authenticate', array(
            'clientToken' => $this->client_token,
            'username' => $this->account_id,
            'password' => $password,
            'agent' => array(
                'name' => 'Minecraft',
                'version' => 1
            )
        ));
        if ($result !== false) {
            $this->access_token = $result['accessToken'];
            $this->client_token = $result['clientToken'];
            $this->name = $result['selectedProfile']['name'];
            $this->uuid = $result['selectedProfile']['id'];
        }
        return $result;
    }

    /**
     * Refresh (and validate) an access token.
     * @return array|bool Response data on success, false on error
     */
    public function refresh() {
        $result = $this->http_auth('refresh', array(
            'clientToken' => $this->client_token,
            'accessToken' => $this->access_token
        ));
        if ($result !== false) {
            $this->access_token = $result['accessToken'];
            $this->client_token = $result['clientToken'];
            $this->name = $result['selectedProfile']['name'];
            $this->uuid = $result['selectedProfile']['id'];
        }
        return $result;
    }

    /**
     * Checks if the current access token is valid.
     * Endpoint returns an empty payload (204 No Content) if successful.
     * @return null|bool NULL on success or false on error
     */
    public function validate() {
        return $this->http_auth('validate', array(
            'accessToken' => $this->access_token
        ));
    }

    /**
     * Invalidates the access token.
     * Endpoint returns an empty payload (204 No Content) if successful.
     * @return null|bool NULL on success or false on error
     */
    public function invalidate() {
        return $this->http_auth('invalidate', array(
            'clientToken' => $this->client_token,
            'accessToken' => $this->access_token
        ));
    }

    /**
     * Invalidates all access tokens that are associated with the account.
     * Endpoint returns an empty payload (204 No Content) if successful.
     * @param string $password Mojang account password
     * @return null|bool NULL on success or false on error
     */
    public function signout($password) {
        return $this->http_auth('signout', array(
            'username' => $this->account_id,
            'password' => $password
        ));
    }

    /**
     * Dispatches a http call to the authentication server with the specified path and data.
     * @param string $path Path to desired endpoint
     * @param array $data  Payload data
     * @return array|bool  Decoded response data or false on error
     */
    private function http_auth($path, $data) {
        return $this->http(self::AUTH_SERVER, $path, 'POST', self::JSON, $data);
    }

    /**
     * Dispatches a http call to a specified url.
     * @param string $host    Server address
     * @param string $path    Path to desired endpoint
     * @param string $method  HTTP method (defaults to GET)
     * @param string $headers HTTP headers [optional]
     * @param string $data    Payload data [optional]
     * @return array|bool     Decoded response data or false on error
     */
    private function http($host, $path, $method = 'GET', $headers = null, $data = null) {
        $response = @file_get_contents(
            $host . $path, false,
            stream_context_create(array(
                'http' => array(
                    'method' => $method,
                    'header' => $headers === null ? "" : $headers,
                    'content' => $data === null ? "" : json_encode($data)
                )
            ))
        );
        if (intval(explode(' ', $http_response_header[0])[1]) >= 400) {
            echo "Unsuccessful request to $host$path: $http_response_header[0]";
            return false;
        } else {
            return json_decode($response, true);
        }
    }

    /**
     * Generates common headers for requests that require an authentication cookie.
     * @return array An array of common request headers
     */
    private function headers() {
        return array(
            "Cookie: sid=token:$this->access_token:$this->uuid;user=$this->name;version=1",
            "Content-Type: application/json",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Connection: keep-alive"
        );
    }

    /**
     * Stores specific fields when class is serialized.
     * @return array Field to keep when serialized.
     */
    public function __sleep() {
        return array(
            'name',
            'uuid',
            'account_id',
            'access_token',
            'client_token'
        );
    }
}