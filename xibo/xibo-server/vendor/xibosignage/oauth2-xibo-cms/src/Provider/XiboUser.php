<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboUser.php)
 */


namespace Xibo\OAuth2\Client\Provider;


use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class XiboUser implements ResourceOwnerInterface
{
    /** @var  int */
    protected $userId;

    /**
     * XiboUser constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->userId = $attributes['userId'];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->userId;
    }
}