<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (ObjectVars.php)
 */


namespace Xibo\OAuth2\Client\Entity;


class ObjectVars
{
    /**
     * Get Object Properties
     * @param $object
     * @return array
     */
    public static function getObjectVars($object)
    {
        return get_object_vars($object);
    }
}