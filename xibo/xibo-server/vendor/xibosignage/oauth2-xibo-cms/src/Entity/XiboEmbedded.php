<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboEmbedded.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboEmbedded extends XiboEntity
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
    public $scaleContent;
    public $embedHtml;
    public $embedScript;
    public $embedStyle;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboEmbedded
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
     * @param $scaleContent
     * @param $embedHtml
     * @param $embedScript
     * @param $embedStyle
     */
    public function create($name, $duration, $useDuration, $transparency, $scaleContent, $embedHtml, $embedScript, $embedStyle, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->transparency = $transparency;
        $this->scaleContent = $scaleContent;
        $this->embedHtml = $embedHtml;
        $this->embedScript = $embedScript;
        $this->embedStyle = $embedStyle;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/embedded/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $transparency
     * @param $scaleContent
     * @param $embedHtml
     * @param $embedScript
     * @param $embedStyle
     */
    public function edit($name, $duration, $useDuration, $transparency, $scaleContent, $embedHtml, $embedScript, $embedStyle, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->transparency = $transparency;
        $this->scaleContent = $scaleContent;
        $this->embedHtml = $embedHtml;
        $this->embedScript = $embedScript;
        $this->embedStyle = $embedStyle;
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
