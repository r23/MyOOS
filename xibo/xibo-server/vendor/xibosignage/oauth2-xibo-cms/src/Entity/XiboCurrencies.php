<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboCurrencies.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboCurrencies extends XiboEntity
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
    public $base;
    public $items;
    public $effect;
    public $speed;
    public $backgroundColor;
    public $noRecordsMessage;
    public $dateFormat;
    public $reverseConversion;
    public $updateInterval;
    public $templateId;
    public $durationIsPerPage;
    public $javaScript;
    public $overrideTemplate;
    public $mainTemplate;
    public $itemTemplate;
    public $styleSheet;
    public $widgetOriginalWidth;
    public $widgetOriginalHeight;
    public $maxItemsPerPage;

    /**
     * Get by Id
     * @param $id
     * @return $this|XiboCurrencies
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
     * @param $templateId
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $base
     * @param $items
     * @param $reverseConversion
     * @param $effect
     * @param $speed
     * @param $backgroundColor
     * @param $noRecordsMessage
     * @param $dateFormat
     * @param $updateInterval
     * @param $durationIsPerPage
     * @param $playlistId
     */
    public function create($templateId, $name, $duration, $useDuration, $base, $items, $reverseConversion, $effect, $speed, $backgroundColor, $noRecordsMessage, $dateFormat, $updateInterval, $durationIsPerPage, $playlistId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->overrideTemplate = 0;
        $this->templateId = $templateId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->base = $base;
        $this->items = $items;
        $this->reverseConversion = $reverseConversion;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->noRecordsMessage = $noRecordsMessage;
        $this->dateFormat = $dateFormat;
        $this->updateInterval = $updateInterval;
        $this->durationIsPerPage = $durationIsPerPage;
        $this->playlistId = $playlistId;
        $response = $this->doPost('/playlist/widget/currencies/' . $playlistId , $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * Edit
     * @param $templateId
     * @param $name
     * @param $duration
     * @param $useDuration
     * @param $base
     * @param $items
     * @param $reverseConversion
     * @param $effect
     * @param $speed
     * @param $backgroundColor
     * @param $noRecordsMessage
     * @param $dateFormat
     * @param $updateInterval
     * @param $durationIsPerPage
     * @param $playlistId
     */
    public function edit($templateId, $name, $duration, $useDuration, $base, $items, $reverseConversion, $effect, $speed, $backgroundColor, $noRecordsMessage, $dateFormat, $updateInterval, $durationIsPerPage, $widgetId)
    {
        $this->userId = $this->getEntityProvider()->getMe()->getId();
        $this->overrideTemplate = 0;
        $this->templateId = $templateId;
        $this->name = $name;
        $this->duration = $duration;
        $this->useDuration = $useDuration;
        $this->base = $base;
        $this->items = $items;
        $this->reverseConversion = $reverseConversion;
        $this->effect = $effect;
        $this->speed = $speed;
        $this->backgroundColor = $backgroundColor;
        $this->noRecordsMessage = $noRecordsMessage;
        $this->dateFormat = $dateFormat;
        $this->updateInterval = $updateInterval;
        $this->durationIsPerPage = $durationIsPerPage;
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
