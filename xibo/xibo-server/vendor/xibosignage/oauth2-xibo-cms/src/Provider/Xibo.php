<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2015 Spring Signage Ltd
 * (Xibo.php)
 */

namespace Xibo\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Xibo\OAuth2\Client\Exception\XiboApiException;

class Xibo extends AbstractProvider
{
    protected $baseUrl;

    public function setCmsUrl($url)
    {
        $this->baseUrl = rtrim($url, '/');
    }

    /**
     * Get CMS Api URL
     * @return string
     */
    public function getCmsApiUrl()
    {
        return rtrim($this->baseUrl, '/') . '/api';
    }

    /**
     * Get the URL that this provider uses to begin authorization.
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getCmsApiUrl() . '/authorize';
    }

    /**
     * Get the URL that this provider uses to request an access token.
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->baseUrl . '/api/authorize/access_token';
    }

    /**
     * Get the URL that this provider uses to request user details.
     *
     * Since this URL is typically an authorized route, most providers will require you to pass the access_token as
     * a parameter to the request. For example, the google url is:
     *
     * 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token='.$token
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->baseUrl . '/api/user/me?access_token=' . $token;
    }

    /**
     * Given an object response from the server, process the user details into a format expected by the user
     * of the client.
     *
     * @param array $response
     * @param AccessToken $token
     * @return XiboUser
     */
    public function createResourceOwner(array $response, AccessToken $token)
    {
        return new XiboUser($response);
    }

    public function getDefaultScopes()
    {
        return [];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        // Check HTTP status
        if ($response->getStatusCode() != 200 && $response->getStatusCode() != 201 && $response->getStatusCode() != 204)
            throw new XiboApiException($response->getBody());

        if (!empty($data['error'])) {
            $message = $data['error']['type'].': '.$data['error']['message'];
            throw new IdentityProviderException($message, $data['error']['code'], $data);
        }
    }

    protected function getAuthorizationHeaders($token = null)
    {
        $token = ($token instanceof AccessToken) ? $token->getToken() : $token;

        return ['Authorization' => 'Bearer ' . $token];
    }
}