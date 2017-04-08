<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboClock.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboClock extends XiboEntity
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
    public $theme;
    public $clockTypeId;
    public $offset;
    public $format;
    public $showSeconds;
    public $clockFace;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboClock
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
     * @param $theme
     * @param $clockTypeId
     * @param $offset
     * @param $format
     * @param $showSeconds
     * @param $clockFace
     */
    public function create($name, $duration, $useDuration, $theme, $clockTypeId, $offset, $format, $showSeconds, $clockFace, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->theme = $theme;
        $this->clockTypeId = $clockTypeId;
        $this->offset = $offset;
        $this->format = $format;
        $this->showSeconds = $showSeconds;
        $this->clockFace = $clockFace;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/clock/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $theme
     * @param $clockTypeId
     * @param $offset
     * @param $format
     * @param $showSeconds
     * @param $clockFace
     */
    public function edit($name, $duration, $useDuration, $theme, $clockTypeId, $offset, $format, $showSeconds, $clockFace, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->theme = $theme;
        $this->clockTypeId = $clockTypeId;
        $this->offset = $offset;
        $this->format = $format;
        $this->showSeconds = $showSeconds;
        $this->clockFace = $clockFace;
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
