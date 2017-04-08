<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboHls.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboHls extends XiboEntity
{
    public $widgetId;
    public $playlistId;
    public $ownerId;
    public $type;
    public $duration;
    public $displayOrder;
    public $useDuration;
    public $calculatedDuration;
    public $widgetOptions;
    public $mediaIds;
    public $audio;
    public $permissions;
    public $module;
    public $name;
    public $uri;
    public $mute;
    public $transparency;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboHls
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet('/playlist/widget', [
            'playlistId' => $id
        ]);

        return clone $this->hydrate($response[0]);
    }
    /**
     * Create
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $uri
     * @param $mute
     * @param $transparency
     */
    public function create($name, $useDuration, $duration, $uri, $mute, $transparency, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->useDuration = $useDuration;
        $this->duration = $duration;
        $this->uri = $uri;
        $this->mute = $mute;
        $this->transparency = $transparency;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/hls/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $uri
     * @param $mute
     * @param $transparency
     */
    public function edit($name, $useDuration, $duration, $uri, $mute, $transparency, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->useDuration = $useDuration;
        $this->duration = $duration;
        $this->uri = $uri;
        $this->mute = $mute;
        $this->transparency = $transparency;
        $this->widgetId = $widgetId;
        $response = $this->doPut('/playlist/widget/' . $widgetId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
    * Delete
    */
    public function delete()
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $response = $this->doDelete('/playlist/widget/' . $this->widgetId , $this->toArray());

        return true;
    }
}
