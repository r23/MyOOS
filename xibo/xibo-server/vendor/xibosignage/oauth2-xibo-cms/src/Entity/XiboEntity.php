<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboEntity.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Provider\XiboEntityProvider;

class XiboEntity
{
    /** @var  XiboEntityProvider */
    private $entityProvider;

    /**
     * @param XiboEntityProvider $provider
     */
    public function __construct($provider)
    {
        $this->entityProvider = $provider;
    }

    /**
     * Hydrate an entity with properties
     *
     * @param array $properties
     * @param array $options
     *
     * @return self
     */
    public function hydrate(array $properties, $options = [])
    {
        $intProperties = (array_key_exists('intProperties', $options)) ? $options['intProperties'] : [];
        $stringProperties = (array_key_exists('stringProperties', $options)) ? $options['stringProperties'] : [];
        $htmlStringProperties = (array_key_exists('htmlStringProperties', $options)) ? $options['htmlStringProperties'] : [];

        foreach ($properties as $prop => $val) {
            if (property_exists($this, $prop)) {

                if (stripos(strrev($prop), 'dI') === 0 || in_array($prop, $intProperties))
                    $val = intval($val);
                else if (in_array($prop, $stringProperties))
                    $val = filter_var($val, FILTER_SANITIZE_STRING);
                else if (in_array($prop, $htmlStringProperties))
                    $val = htmlentities($val);

                $this->{$prop} =  $val;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ObjectVars::getObjectVars($this);
    }

    /**
     * @return XiboEntityProvider
     */
    protected function getEntityProvider()
    {
        return $this->entityProvider;
    }

    /**
     * @param $url
     * @param $params
     * @return mixed
     */
    protected function doGet($url, $params = [])
    {
        return $this->getEntityProvider()->get($url, $params);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    protected function doPost($url, $params = [])
    {
        return $this->getEntityProvider()->post($url, $params);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    protected function doPut($url, $params = [])
    {
        return $this->getEntityProvider()->put($url, $params);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    protected function doDelete($url, $params = [])
    {
        return $this->getEntityProvider()->delete($url, $params);
    }
}