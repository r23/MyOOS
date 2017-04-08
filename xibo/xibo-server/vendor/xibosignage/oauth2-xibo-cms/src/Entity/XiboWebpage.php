<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboWebpage.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboWebpage extends XiboEntity
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
    public $transparency;
    public $uri;
    public $scaling;
    public $offsetLeft;
    public $offsetTop;
    public $pageWidth;
    public $pageHeight;
    public $modeId;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboWebpage
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
     * @param $transparency
     * @param $uri
     * @param $scaling
     * @param $offsetLeft
     * @param $offsetTop
     * @param $pageWidth
     * @param $pageHeight
     * @param $modeId
     */
    public function create($name, $duration, $useDuration, $transparency, $uri, $scaling, $offsetLeft, $offsetTop, $pageWidth, $pageHeight, $modeId, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->transparency = $transparency;
        $this->uri = $uri;
        $this->scaling = $scaling;
        $this->offsetLeft = $offsetLeft;
        $this->offsetTop = $offsetTop;
        $this->pageWidth = $pageWidth;
        $this->pageHeight = $pageHeight;
        $this->modeId = $modeId;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/webpage/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $transparency
     * @param $uri
     * @param $scaling
     * @param $offsetLeft
     * @param $offsetTop
     * @param $pageWidth
     * @param $pageHeight
     * @param $modeId
     */
    public function edit($name, $duration, $useDuration, $transparency, $uri, $scaling, $offsetLeft, $offsetTop, $pageWidth, $pageHeight, $modeId, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->transparency = $transparency;
        $this->uri = $uri;
        $this->scaling = $scaling;
        $this->offsetLeft = $offsetLeft;
        $this->offsetTop = $offsetTop;
        $this->pageWidth = $pageWidth;
        $this->pageHeight = $pageHeight;
        $this->modeId = $modeId;
        $this->widgetId = $widgetId;
        $response = $this->doPut('/playlist/widget/webpage/' . $playlistId , $this->toArray());

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

