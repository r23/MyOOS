<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboLocalVideo.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboLocalVideo extends XiboEntity
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
    public $uri;
    public $scaleTypeId;
    public $mute;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboLocalVideo
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
     * @param $uri
     * @param $duration
     * @param $useDuration
     * @param $scaleTypeId
     * @param $mute
     */
    public function create($uri, $duration, $useDuration, $scaleTypeId, $mute, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->uri = $uri;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->scaleTypeId = $scaleTypeId;
        $this->mute = $mute;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/localVideo/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }
    /**
     * Edit
     * @param $uri
     * @param $duration
     * @param $useDuration
     * @param $scaleTypeId
     * @param $mute
     */
    public function edit($uri, $duration, $useDuration, $scaleTypeId, $mute, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->uri = $uri;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->scaleTypeId = $scaleTypeId;
        $this->mute = $mute;
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
