<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboVideo.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboVideo extends XiboEntity
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
    public $scaleTypeId;
    public $mute;
    public $loop;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboVideo
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
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $scaleTypeId
     * @param $mute
     * @param $loop
     */
    public function edit($name, $duration, $useDuration, $scaleTypeId, $mute, $loop, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->scaleTypeId = $scaleTypeId;
        $this->mute = $mute;
        $this->loop = $loop;
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
