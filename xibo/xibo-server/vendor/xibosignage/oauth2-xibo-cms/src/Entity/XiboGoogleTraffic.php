<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboGoogleTraffic.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboGoogleTraffic extends XiboEntity
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
    public $useDisplayLocation;
    public $longitude;
    public $latitude;
    public $zoom;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboGoogleTraffic
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
     * @param $useDisplayLocation
     * @param $longitude
     * @param $latitude
     * @param $zoom
     */
    public function create($name, $duration, $useDuration, $useDisplayLocation, $longitude, $latitude, $zoom, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->useDisplayLocation = $useDisplayLocation;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->zoom = $zoom;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/googleTraffic/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $useDisplayLocation
     * @param $longitude
     * @param $latitude
     * @param $zoom
     */
    public function edit($name, $duration, $useDisplayLocation, $longitude, $latitude, $zoom, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDisplayLocation = $useDisplayLocation;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->zoom = $zoom;
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
