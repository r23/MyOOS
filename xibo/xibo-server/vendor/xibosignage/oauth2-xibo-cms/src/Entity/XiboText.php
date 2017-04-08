<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboText.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboText extends XiboEntity
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
    public $effect;
    public $speed;
    public $backgroundColor;
    public $marqueeInlineSelector;
    public $text;
    public $javaScript;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboText
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
     * @param $effect
     * @param $speed
     * @param $backgroundColor
     * @param $marqueeInlineSelector
     * @param $text
     * @param $javaScript
     */
    public function create($name, $duration, $useDuration, $effect, $speed, $backgroundColor, $marqueeInlineSelector, $text, $javaScript, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->marqueeInlineSelector = $marqueeInlineSelector;
        $this->text = $text;
        $this->javaScript = $javaScript; 
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/text/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $name
     * @param $duration
     * @param $effect
     * @param $speed
     * @param $backgroundColor
     * @param $marqueeInlineSelector
     * @param $text
     * @param $javaScript
     */
    public function edit($name, $duration, $useDuration, $effect, $speed, $backgroundColor, $marqueeInlineSelector, $text, $javaScript, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->marqueeInlineSelector = $marqueeInlineSelector;
        $this->text = $text;
        $this->javaScript = $javaScript; 
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
