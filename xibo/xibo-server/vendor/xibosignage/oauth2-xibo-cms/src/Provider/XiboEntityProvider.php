<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboEntity.php)
 */


namespace Xibo\OAuth2\Client\Provider;
use GuzzleHttp\Psr7\MultipartStream;
use League\OAuth2\Client\Token\AccessToken;
use Xibo\OAuth2\Client\Exception\EmptyProviderException;

class XiboEntityProvider
{
    /** @var  Xibo */
    private $provider;
    /** @var  XiboUser */
    private $me;
    /** @var  AccessToken */
    private $token;
    
    /**
     * Set Provider
     * @param Xibo $provider
     */
    public function __construct($provider)
    {
        $this->provider = $provider;
    }
    
    /**
     * Get Provider
     * @return Xibo
     */
    public function getProvider()
    {
        return $this->provider;
    }
    
    /**
     * Get Me
     * @return XiboUser
     */
    public function getMe()
    {
        if ($this->me == null) {
            $this->me = $this->provider->getResourceOwner($this->getAccessToken());
        }
        return $this->me;
    }
    
    /**
     * Get Access Token
     * @return AccessToken
     * @throws \Exception
     */
    private function getAccessToken()
    {
        if ($this->provider === null)
            throw new EmptyProviderException();
        if ($this->token == null || $this->token->hasExpired()) {
            // Get and store a new token
            $this->token = $this->provider->getAccessToken('client_credentials');
        }
        return $this->token;
    }
    
    /**
     * @param $url
     * @param $params
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($url, $params = [])
    {
        return $this->request('GET', $url . '?' . http_build_query($params));
    }
    
    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    public function post($url, $params = [])
    {
        return $this->request('POST', $url, $params);
    }
    
    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    public function put($url, $params = [])
    {
        return $this->request('PUT', $url, $params);
    }
    
    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    public function delete($url, $params = [])
    {
        return $this->request('DELETE', $url, $params);
    }
    
    /**
     * Request
     * @param $method
     * @param $url
     * @param array $params
     * @return mixed
     * @throws EmptyProviderException
     */
    private function request($method, $url, $params = [])
    {
        $options = [
            'headers' => null, 'body' => null
        ];
        // Multipart
        if (array_key_exists('multipart', $params)) {
            // Build the multipart message
            $options['body'] = new MultipartStream($params['multipart']);
        } else if (array_key_exists('json', $params)) {
            // Build the JSON body and content type
            $options['body'] = json_encode($params['json']);
            $options['headers'] = ['content-type' => 'application/json'];
        } else if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
            $options['headers'] = ['content-type' => 'application/x-www-form-urlencoded'];
            if (count($params) > 0) {
                $options['body'] = http_build_query($params, null, '&');
            }
        }
        $request = $this->provider->getAuthenticatedRequest($method, $this->provider->getCmsApiUrl() . rtrim($url, '/'), $this->getAccessToken(), $options);
        return $this->provider->getResponse($request);
    }
}
